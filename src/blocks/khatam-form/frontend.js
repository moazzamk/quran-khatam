import { render, useState, useEffect } from '@wordpress/element';
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
  Snackbar,
  Alert,
} from '@mui/material';
import CheckIcon from '@mui/icons-material/Check';
import CloseIcon from '@mui/icons-material/Close';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 
import { green, grey, red } from '@mui/material/colors';
import { Stack } from '@mui/system';

const theme = createTheme({
  typography: {
    allVariants: {
      fontFamily: "'Open Sans', Roboto, sans-serif",
    },
  },
  components: {
    MuiFormLabel: {
      styleOverrides: {
        asterisk: {color:"red"},
      },
    },
  },
});

import icons from '../../icons';

function KhatamForm ({ availableSpots }) {
  const [openSlots, setOpenSlots] = useState(availableSpots);
  const [ formType, setFormType ] = useState(0);
  const [ namesField, setNamesField ] = useState();
  const [ names, setNames ] = useState([]);
  const [ email, setEmail ] = useState('');
  const [ isFormValid, setIsFormValid ] = useState(true);
  
  // ALERT VARS
  const [ showAlert, setShowAlert ] = useState(false);
  const [ alertMsg, setAlertMsg ] = useState('');
  const [ alertSev, setAlertSev ] = useState('');

  // NAME(S) VALIDATION VARS
  const [ isKhatamFull, setIsKhatamFull ] = useState(false);
  const [ isNamesError, setIsNamesError ] = useState(false);
  const [ namesError, setNamesError ] = useState('');

  function resetAlert() {
    setShowAlert(false);
    setAlertSev('');
  }

  function showError() {
    setShowAlert(true);
    setAlertSev('error');
  }

  function resetNamesError() {
    setIsNamesError(false);
    setNamesError('');
  }

  // VALIDATION - SIGN UP - NAMES FIELD
  useEffect(() => {
    setIsFormValid(true);
    resetNamesError();
    setNames(namesField == null ? [] : [...namesField.split(', ')]);
  }, [namesField, openSlots, formType])

  useEffect(() => {
    // IF NAMES contain leading or trailing spaces and commas
    if (
      names.length != 0 &&
      names.some(n =>
        n.slice(1) == ' ' ||
        n.slice(-1) == ' ' ||
        n.slice(1) == ',' ||
        n.slice(-1) == ','
      )
    ) {
      setIsFormValid(false);
      setIsNamesError(true);
      setNamesError('Names cannot begin or end with commas or spaces');
    }

    // formType: 0 = signing up for recitation, 1 = completed recitation
    if (formType == 0) {
      // IF NAMES ARE MISSING LAST NAMES
      if (names.length > 0 && !names.every(name => name.includes(' '))) {
        setIsFormValid(false);
        setIsNamesError(true);
        setNamesError('All names MUST contain a last name.');
      }

      // IF NAMES > 7
      if (names.length > 7) {
        setIsFormValid(false);
        setIsNamesError(true);
        setNamesError('Cannot add more than 7 names');
      }

      // IF NAMES > AVAILABLE SLOTS
      if (names.length > openSlots) {
        setIsFormValid(false);
        let noOfUsersToRemove = names.length - openSlots;
        setAlertMsg(`
          There ${openSlots > 1 ? 'are' : 'is'} only ${openSlots} open spot${openSlots > 1 ? 's' : ''} in current khatam. 
          Please remove ${noOfUsersToRemove} name${noOfUsersToRemove > 1 ? 's': ''} and try again.
        `);
      }
    } else if (formType == 1) {

    }
  }, [names])

  // SHOW ERROR ALERT -- IF CURRENT KHATAM IS FULL
  useEffect(() => {
    setIsKhatamFull(+openSlots == 0 ? true : false);
    console.log('formType: ' + formType);
    if (isKhatamFull) {
      setShowAlert(true);
      setAlertMsg('Current Khatam is full!');
      setAlertSev('error');
    }
  }, [openSlots, isKhatamFull]);

  async function handleKhatamFormSubmit (e) {
    e.preventDefault();

    resetAlert();
    if (!isFormValid) {
      // setAlertMsg('Please fix the errors below and try again.')
      showError();
    }

    const formData = {
      names: names,
      email: email
    }
    
    if (+formType === 0) {
      await handleSignup(formData)
    } else if (+formType === 1) {
      console.log('send data for completing a juz');
    }
  }

  useEffect(() => { console.log(openSlots)}, [openSlots]);

  async function handleSignup(formData) {
    if (isFormValid) {
      await (
        await fetch(
          kh_auth_rest.users,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
          }
        )
      ).json().then(data => {
        setOpenSlots(data.openSlots);

        const userTableUpdated = new Event ('khatamUpdated');
        const khDataBlocks = document.querySelectorAll('.kh-users');
        
        khDataBlocks.forEach(block => 
          block.dispatchEvent(userTableUpdated)
        );
      });
    } else {
      showError('Please fix the errors and try again!');
    }
  }

  return (
    <ThemeProvider theme={ theme }>
      <Card elevation={2} sx={{ backgroundColor: grey[50]}}>
        <CardHeader 
          title="Khatam Recitation Form"
          openIcon={icons.logo}
          color="secondary"
        />
        <Divider variant="middle" />
        <form onSubmit={handleKhatamFormSubmit}>

          {/* ERROR MESSAGES HERE */}
          { showAlert && (
            <Alert 
              severity={ alertSev } 
              sx={{ margin: "1rem"}}
            >{ alertMsg }</Alert>
          )}

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
                    value={0} 
                    control={<Radio />} 
                    label="I want to recite a juz"
                    disabled={ isKhatamFull }
                  />
                  {isKhatamFull && <div>"Current khatam is full"</div>}
                  <FormControlLabel 
                    value={1} 
                    control={<Radio />} 
                    label="I completed recitation"
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
                    "Must be comma separated"
                  }
                  error={ isNamesError }
                  required
                  value={ namesField }
                  onChange={e => setNamesField(e.target.value)}
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
    </ThemeProvider>
  );
}


document.addEventListener('DOMContentLoaded', () => {
  const block = document.querySelector('#kh-form-container');
  const availableSpots = +block.dataset.availableSpots;

  render(
    <KhatamForm availableSpots={ availableSpots } />,
    block
  )
  


});
