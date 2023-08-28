import { render, useState, useEffect, useRef } from '@wordpress/element';
import {
	Button,
	Card, 
	CardContent, 
	CardHeader,
	CardActions, 
	Divider, 
	Typography,
	FormControl,
	FormLabel,
	FormControlLabel,
	RadioGroup,
	Radio,
	TextField,
} from '@mui/material';

import { grey } from '@mui/material/colors';
import { Stack } from '@mui/system';

import icons from '../../../../icons';

import SuccessTable from '../SucessTable/SuccessTable';


export default function ReciteForm ({
  availableSpots,
  alertMsg,
  setAlertMsg, 
  setDoAlertReset,
  setModalHTML,
  setShowModal, 
  alertRef
}) {
  const [ formType, setFormType ] = useState(0);
  const [ names, setNames ] = useState([]);
  const [ email, setEmail ] = useState('');
  const [ isFormValid, setIsFormValid ] = useState(true);
  const [addedUsers, setAddedUsers] = useState([]);

  const [openSlots, setOpenSlots] = useState(availableSpots);

  function throwNamesError (msg) {
    setIsFormValid(false);
    setIsNamesError(true);
    setNamesError(msg);
  }

  function resetNamesError() {
    setIsNamesError(false);
    setNamesError('');
  }

  function showError (msg) {
    setAlertMsg(msg);
    window.scrollTo({
      top: alertRef.current.offsetTop,
      behavior: 'smooth',
    });
  }

  // Radio buttons
  const [signupBtnChecked, setSignupBtnChecked] = useState(false);
  const [completedBtnChecked, setCompletedBtnChecked] = useState(false);
  useEffect( () => {
    if (+formType === 1) {
      setCompletedBtnChecked(false);
    } else if (+formType === 2) {
      setSignupBtnChecked(false);
    }
  }, [formType]);

  // NAME(S) VALIDATION VARS
  const [ isKhatamFull, setIsKhatamFull ] = useState(false);
  const [ isNamesError, setIsNamesError ] = useState(false);
  const [ namesError, setNamesError ] = useState('');

  // Validation - NAMES
  function validateNames (e) {
    e.preventDefault();

    const namesStr = e.target.value;

    if (
      namesStr == null ||
      namesStr == undefined ||
      namesStr == ''
    ) {
      return resetNamesError();
    }

    // If names input does not contain any letters
    const containsLetters = /[A-z]/g;
    if (!containsLetters.test(namesStr)) {
      e.target.value = '';
    }

    // Split names into an array, setNames and trigger the useEffect
    setNames(
      [...namesStr.trim()
        .toLowerCase()
        .replace(/(^[,\s]*)|([,\s]*$)/g, '')
        .replace(/(,\s+)|(\s,)/g, ',')
        .split(',')
      ]
    );
  }

  // UseEffect for names validation
  useEffect(()=>{
    if (names.length <= 0) {
      resetNamesError();
      return;
    }

    // If names are missing a last name
    if (!names?.every(name => name.includes(' '))) {
      throwNamesError('All names MUST contain a last name.');
      return;
    }

    // If names > 7
    if (names.length > 7) {
      throwNamesError('Cannot add more than 7 names');
      return;
    }

    // aa aa, bb bb, cc cc, dd dd, ee ee, ff ff, gg gg, hh hh
    // Name validations for signing up
    if (+formType === 1) {
      // If names > available slots
      if (names.length > openSlots) {
        let noOfUsersToRemove = names.length - openSlots;
        showError(`
          There ${openSlots > 1 ? 'are' : 'is'} only ${openSlots} open spot${openSlots > 1 ? 's' : ''} in current khatam. 
          Please remove ${noOfUsersToRemove} name${noOfUsersToRemove > 1 ? 's': ''} and try again.
        `);
        setIsNamesError(true);
        return;
      } else {
        setAlertMsg(null)
      }
    // Name validations for completing juz
    } else if (+formType === 1) {
      // Do something ...
    }

    setIsFormValid(true);
    resetNamesError();
  }, [names, isFormValid, isNamesError, formType]);

  // SHOW ERROR ALERT -- IF CURRENT KHATAM IS FULL
  useEffect(() => {
    setIsKhatamFull(+openSlots == 0 ? true : false);
    if (isKhatamFull) {
      setSignupBtnChecked(false);
      showError('Current Khatam is full!');
    }
    console.log(isKhatamFull);
  }, [openSlots, isKhatamFull]);

  useEffect(() => {}, [openSlots, isNamesError, addedUsers]);

  // EMAIL VALIDATION
  const [emailError, setEmailError] = useState(null);
  const [isEmailError, setIsEmailError] = useState(false)
  function validateEmail(e) {
    e.preventDefault();
    const emailStr = e.target.value;
    const isEmailValid = emailStr.match(
      /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );

    if (!isEmailValid) {
      setIsEmailError(true);
      setEmailError("Enter a valid email address")
    } else {
      setIsEmailError(false);
      setEmailError(null);
    }
  }

  async function handleKhatamFormSubmit (e) {
    e.preventDefault();
    const formData = {
      names: names,
      email: email
    }

    // If no option is selected
    if (+formType !== 1 && +formType !== 2) {
      showError('Please select the appropriate option');
    }
  
    // If current khatam is full
    if (+formType === 1 && isKhatamFull) {
      showError('Current Khatam is full!');
      return;
    }

    // If not enough spots available
    if (isNamesError && alertMsg !== null) {
      showError(alertMsg);
      return;
    }

    // If names are invalid
    if (isNamesError) {
      showError('Please fix the errors below and try again.');
      return;
    }

    // If email is invalid?

    // Reset Alert and form
    setDoAlertReset(true);
    setIsFormValid(true);

    if (+formType === 1) {
      await handleSignup(formData)
    } else if (+formType === 2) {
      handleJuzCompleted(formData);
    }
  }

  async function handleSignup(formData) {
    if (isFormValid) {
      await (
        await fetch(
          kh_auth_rest.signup,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
          }
        )
      ).json().then(data => {
        if (data.status == 1) {
          setAlertMsg(data.msg);
        } else {
          setOpenSlots(data.openSlots);

          const userTableUpdated = new Event ('khatamUpdated');
          const khDataBlocks = document.querySelectorAll('.kh-users');

          khDataBlocks.forEach(block => 
            block.dispatchEvent(userTableUpdated)
          );

          setAddedUsers(data.results);
          setModalHTML(
            <SuccessTable users={addedUsers}/>
          );
          setShowModal(true);
        }
      });
    } else {
      showError('Please fix the errors and try again!');
    }
  }

  async function handleJuzCompleted(formData) {
    await (
      await fetch(
        kh_auth_rest.completejuz,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData),
        }
      )
    ).json().then(data => {
      if (data.status == 1) {
        setAlertMsg(data.msg);
      } else {
        // console.log(data);
        // setAddedUsers(data.results);
        setShowModal(true);

        const userTableUpdated = new Event ('khatamUpdated');
        const khDataBlocks = document.querySelectorAll('.kh-users');
        
        khDataBlocks.forEach(block => 
          block.dispatchEvent(userTableUpdated)
        );
      }
    });
  }

  return (
    <Card elevation={2} sx={{ backgroundColor: grey[50]}}>
      <CardHeader 
        title="Khatm Recitation Form"
        openIcon={icons.logo}
        color="secondary"
      />
      <Divider variant="middle" />
      <form onSubmit={handleKhatamFormSubmit}>
        <CardContent sx={{ paddingLeft: "2rem"}}>
          <Stack spacing={2}>
            <FormControl>
              <FormLabel id="kh-form-type" required>
                <Typography variant="body2" component="span">
                  Please Select One
                </Typography>
              </FormLabel>
              <RadioGroup
                aria-labelledby='kh-form-type'
                name="khFormType"
                onChange={e => setFormType(e.target.value)}
              >
                <FormControlLabel
                  value={1}
                  control={<Radio size="small" />}
                  label={isKhatamFull ?
                    <span>Current khatam is full</span> :
                    "I want to recite"
                  }
                  disabled={ isKhatamFull }
                  checked={ signupBtnChecked }
                  onChange={e => setSignupBtnChecked(!!e.target.value)}
                />
                <FormControlLabel
                  value={2}
                  control={<Radio size="small" />}
                  label="I completed recitation"
                  checked={ completedBtnChecked }
                  onChange={e => setCompletedBtnChecked(!!e.target.value)}
                />
              </RadioGroup>
            </FormControl>
            <FormControl>
              <TextField 
                id="khName" 
                label="Name(s)" 
                variant="standard" 
                helperText={ 
                  isNamesError ? 
                  namesError : 
                  'Names must be comma separated'
                }
                error={ isNamesError }
                required
                // onChange={e => setNamesField(e.target.value)}
                onBlur={e => validateNames(e)}
              />
            </FormControl>
            <FormControl>
              <TextField 
                id="khEmail" 
                label="Email" 
                variant="standard" 
                required
                value={ email }
                onChange={e => setEmail(e.target.value)}
                onBlur={e => validateEmail(e)}
                helperText={ 
                  isEmailError ? 
                  emailError : 
                  null
                }
                error={ isEmailError }
              />
            </FormControl>
          </Stack>

        </CardContent>
        <CardActions sx={{justifyContent: "flex-end"}}>
          <Button variant="standard" type='submit'>
            <Typography 
              variant="subtitle2" 
              color="secondary" 
              sx={{ fontWeight: 700 }}
            >
              Submit
            </Typography>
          </Button>
        </CardActions>
      </form>
    </Card>
  );
}