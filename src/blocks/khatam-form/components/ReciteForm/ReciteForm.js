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
  FormHelperText,
	FormControlLabel,
	RadioGroup,
	Radio,
	TextField,
} from '@mui/material';

import { grey, red } from '@mui/material/colors';
import { Stack } from '@mui/system';

import icons from '../../../../icons';

import SuccessTable from '../SucessTable/SuccessTable';


export default function ReciteForm ({
  availableSpots,
  alertMsg,
  setAlertMsg, 
  setDoAlertReset,
  modalArr,
  alertRef,
}) {
  // const [namesArr, setNamesArr] = useState([]);
  const khFormDefaultState = {
    formTypeRadioGroup: {
      recite: {
        enabled: true,
        label: 'I want to recite',
        selected: false,
        value: 'recite',
        isChecked: null,
      },
      completed: {
        enabled: true,
        label: 'I completed recitation',
        selected: false,
        value: 'completed',
        isChecked: null,
      },
      radioSelected: null,
      prevRadioSelected: null,
      isValid: true,
      helperText: ''
    },
    names: {
      value: '',
      isValid: true,
      helperTextError: '',
      helperTextDefault: 'Names must be comma separated',
      label: 'Name(s)',
    },
    email: {
      value: '',
      isValid: true,
      helperTextError: '',
      helperTextDefault: '',
    },
    isValid: false,
    openSlots: availableSpots,
    isKhatamFull: availableSpots <= 0,
  };
  const [khForm, setKhForm] = useState(khFormDefaultState);
  const[modal, setModal] = modalArr;


  function showError (msg) {
    setAlertMsg(msg);
    window.scrollTo({
      top: alertRef.current.offsetTop,
      behavior: 'smooth',
    });
  }

  function getNamesArray () {
    return khForm.names.value
      .trim()
      .toLowerCase()
      .replace(/(^[,\s]*)|([,\s]*$)/g, '')
      .replace(/(,\s+)|(\s,)/g, ',')
      .split(',')
      .filter(n => n)
    ;
  }

  function resetNames () {
    setKhForm({...khForm,
      isValid: false,
      names: { ...khForm.names,
        value: '',
        values: [],
        isValid: true,
        helperText: null
      }
    })
  }

  function resetEmail () {
    setKhForm({ ...khForm,
      isValid: false,
      email: { ...khForm.email,
        isValid: true,
        helperText: null
      }
    })
  }

  useEffect(() => {
    if (
      khForm.names.isValid && 
      khForm.email.isValid && 
      khForm.formTypeRadioGroup.radioSelected != null &&
      khForm.isValid === false
    ) {
      setKhForm({...khForm,
        isValid: true,
      })
    }

    if (
      khForm.isKhatamFull && 
      khForm.formTypeRadioGroup.recite.label === khFormDefaultState.formTypeRadioGroup.recite.label
    ) {
      setKhForm({...khForm,
        formTypeRadioGroup:{...khForm.formTypeRadioGroup,
          recite: {...khForm.formTypeRadioGroup.recite,
            label: <Typography sx={{color: grey[400]}}>Current khatam is full</Typography>,
          },
          radioSelected: khForm.formTypeRadioGroup.completed.value,
        }
      })
    }

    // if (khForm.formTypeRadioGroup.radioSelected != khForm.formTypeRadioGroup.prevRadioSelected) {
    //   setKhForm({...khForm,
    //     formTypeRadioGroup: {...khForm.formTypeRadioGroup,
    //       prevRadioSelected: khForm.formTypeRadioGroup.radioSelected,
    //     }
    //   });
    // }
    console.log(khForm);
  }, [khForm]);

  function validateNames () {
    if (khForm.names.value == null || khForm.names.value === '') {
      resetNames();
    } else {
      // If names input does not contain any letters
      const containsLetters = /[A-z]/g;
      if (!containsLetters.test(khForm.names.value)) {
        resetNames();
      }

      let namesArr = getNamesArray();

      // aa aa, bb bb ,,,,,,,,,,  ccc ccc
      if (namesArr?.some(name => name.includes(null))) {
        setKhForm({...khForm,
          isValid: false,
          names: {
            ...khForm.names,
            isValid: false,
            helperTextError: 'Invalid name(s)'
          }
        });
        return;
      }

      if (!namesArr?.every(name => name.includes(' '))) {
        setKhForm({...khForm,
          isValid: false,
          names: {
            ...khForm.names,
            isValid: false,
            helperTextError: 'All names MUST contain a last name.'
          }
        });
        return;
      }

      if (namesArr.length > 7) {
        setKhForm({...khForm,
          isValid: false,
          names: {
            ...khForm.names,
            isValid: false,
            helperTextError: 'Cannot add more than 7 names'
          }
        });
        return;
      }

      if (khForm.formTypeRadioGroup.radioSelected === 'recite') {
        if (namesArr.length > khForm.openSlots) {
          let noOfUsersToRemove = namesArr.length - khForm.openSlots;
          setKhForm({...khForm,
            isValid: false,
            names: {
              ...khForm.names,
              isValid: false,
              helperTextError: `
              There ${khForm.openSlots > 1 ? 'are' : 'is'} only ${khForm.openSlots} open spot${khForm.openSlots > 1 ? 's' : ''} in current khatam. 
              Please remove ${noOfUsersToRemove} name${noOfUsersToRemove > 1 ? 's': ''} and try again.
            `
            }
          });
          return;
        } else {
          setAlertMsg(null);
        }
      }

      setKhForm({...khForm,
        names: {...khForm.names,
          values: namesArr,
          isValid: true,
          helperTextError: null
        }
      });
    }
  }

  function validateEmail () {
    if (khForm.email.value == null || khForm.email.value === '') {
      resetEmail();
    } else {
      const isEmailValid = !!khForm.email.value.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );

      if (!!!isEmailValid) {
        setKhForm({
          ...khForm,
          email: {...khForm.email,
            isValid: false,
            helperTextError: 'Please enter a valid email address',
          }
        })
      } else {
        setKhForm({
          ...khForm,
          email: {...khForm.email,
            isValid: true,
            helperTextError: null,
          }
        })
      }
    }
  }

  function handleFormTypeChange (e) {
    let selectedValue = khForm.isKhatamFull ? 
      khForm.formTypeRadioGroup.completed.value : e.target.value;

    setKhForm({
      ...khForm,
      formTypeRadioGroup: { 
        ...khForm.formTypeRadioGroup, 
        radioSelected: selectedValue
      }
    })
  }

  async function handleKhatamFormSubmit (e) {
    e.preventDefault();
    const formData = {
      names: getNamesArray(),
      email: khForm.email.value,
    };

    // if (!khForm.isValid) {
    //   return;
    // }

    if (
      khForm.names.isValid && 
      khForm.email.isValid && 
      khForm.formTypeRadioGroup.radioSelected != null
    ) {
      setKhForm({...khForm,
        isValid: true,
      })
    }

    if (khForm.formTypeRadioGroup.radioSelected == 'recite') {
      if (khForm.isKhatamFull) {
        setModalTitle('Current khatam is full!');
        setModalHTML(
          <Typography>Unable to add users to current kharam as it is full!</Typography>
        );
        setShowModal(true);
        return;
      }

      await handleSignup(formData);
    } else if (khForm.formTypeRadioGroup.radioSelected == 'completed') {
      await handleJuzCompleted(formData);
    } else {
      setKhForm({...khForm, 
        formTypeRadioGroup: {...khForm.formTypeRadioGroup,
          isValid: false,
          helperText: "Please select an appropriate option",
        }
      })
    }
  }

  async function handleSignup (formData) {
    if (khForm.isValid) {
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
          setModal({...modal,
            showModal: true,
            severity: 'warning',
            modalTitle: 'Error',
            modalText: data.msg,
            children: null,
          });
        } else {
          let isKhatamFull = data.openSlots <= 0;

          setKhForm({...khFormDefaultState,
            openSlots: data.openSlots,
            isKhatamFull: isKhatamFull,
          });

          const userTableUpdated = new Event ('khatamUpdated');
          const khDataBlocks = document.querySelectorAll('.kh-users');

          khDataBlocks.forEach(block => 
            block.dispatchEvent(userTableUpdated)
          );

          setModal({...modal,
            showModal: true,
            severity: 'success',
            modalTitle: 'Success',
            modalText: "The following users were successfully added to current khatam:",
            children: [<SuccessTable users={data.results}/>],
          });
        }
      });
    } else {
      setModal({...modal,
        showModal: true,
        severity: 'warning',
        modalTitle: 'Error',
        modalText: "Please fix the errors and try again!",
        children: [],
      });
    }
  }

  async function handleJuzCompleted (formData) {
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
        setModal({...modal,
          showModal: true,
          severity: 'warning',
          modalTitle: 'Error',
          modalText: data.msg,
          children: null,
        })
      } else {
        setModal({...modal,
          showModal: true,
          severity: 'success',
          modalTitle: 'Success',
          modalText: "The user(s) were successfully updated",
          children: null,
        });

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
              <FormLabel id="kh-form-type" error={ !khForm.formTypeRadioGroup.isValid } required>
                <Typography variant="body2" component="span">
                  Please Select One
                </Typography>
              </FormLabel>
              <RadioGroup
                aria-labelledby='kh-form-type'
                name="khFormType"
                value={ khForm.radioSelected }
                onChange={ e => {
                  setKhForm({...khForm,
                    formTypeRadioGroup: {...khForm.formTypeRadioGroup,
                      isValid: true,
                      helperText: '',
                      radioSelected: e.target.value,
                      prevRadioSelected: khForm.formTypeRadioGroup.radioSelected,
                    }
                  });
                }}
              >
                <FormControlLabel
                  value={khForm.formTypeRadioGroup.recite.value}
                  control={<Radio size="small" />}
                  label={ khForm.formTypeRadioGroup.recite.label }
                  disabled={ khForm.isKhatamFull }
                />
                <FormControlLabel
                  value={khForm.formTypeRadioGroup.completed.value}
                  control={<Radio size="small" />}
                  label={ khForm.formTypeRadioGroup.completed.label }
                />
              </RadioGroup>
              <FormHelperText error={ !khForm.formTypeRadioGroup.isValid }>
                { khForm.formTypeRadioGroup.helperText }
              </FormHelperText>
            </FormControl>
            <FormControl>
              <TextField 
                id="khName" 
                value={ khForm.names.value }
                label={ khForm.names.label }
                helperText={ khForm.names.isValid ? 
                  khForm.names.helperTextDefault :
                  khForm.names.helperTextError
                }
                error={ !khForm.names.isValid }
                required
                variant="standard" 
                onChange={e => setKhForm({...khForm,
                  names: { ...khForm.names, value: e.target.value }
                })}
                onBlur={ validateNames }
              />
            </FormControl>
            <FormControl>
              <TextField 
                id="khEmail" 
                label="Email" 
                variant="standard" 
                required
                value={ khForm.email.value }
                onChange={e => setKhForm({...khForm, 
                  email: { ...khForm.email, 
                    value: e.target.value
                  }
                })}
                onBlur={validateEmail}
                helperText={ khForm.email.isValid ? 
                  khForm.email.helperTextDefault :
                  khForm.email.helperTextError
                }
                error={ !khForm.email.isValid }
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