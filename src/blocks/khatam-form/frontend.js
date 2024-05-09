import { render, useState, useEffect, useRef } from '@wordpress/element';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 
import { Alert } from '@mui/material';
import { green, red, orange } from '@mui/material/colors';

import ModalAlert from './components/ModalALert/ModalAlert';
import ReciteForm from './components/ReciteForm/ReciteForm';

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
  // ALERT VARS
  const [ showAlert, setShowAlert ] = useState(false);
  const [ alertMsg, setAlertMsg ] = useState('');
  const [ alertSev, setAlertSev ] = useState('');
  const [doAlertReset, setDoAlertReset] = useState(true);

  const [modal, setModal] = useState({
    showModal: false,
    modalTitle: '',
    modalText: '',
    severity: 'success',
    modalStates: {
      success: {
        background: green['50'],
        fontColor: green[800],
      },
      warning: {
        background: orange['50'],
        fontColor: orange[800],
      },
      error: {
        background: red['50'],
        fontColor: red[800],
      },
    },
    children: [],
  })

  const alertRef = useRef();

  function resetAlert() {
    setShowAlert(false);
    setAlertSev('');
  }

  function showAlertError() {
    setShowAlert(true);
    setAlertSev('error');
  }

  useEffect(() => {
    if (alertMsg == '' || alertMsg == null || alertMsg == undefined) {
      resetAlert();
    } else {
      showAlertError();
    }
  }, [alertMsg]);

  useEffect(() => {
    if (doAlertReset) {
      resetAlert();
    }
  }, [doAlertReset])

  useEffect(() => {}, modal);

  return (
    <ThemeProvider theme={ theme }>
      <ModalAlert modalArr={[modal, setModal]} />
      <Alert severity={alertSev} ref={alertRef} sx={{
        marginBottom: 2,
      }} >{ alertMsg }</Alert>

      <ReciteForm 
        availableSpots={availableSpots} 
        alertMsg = {alertMsg}
        setAlertMsg={setAlertMsg}
        setDoAlertReset={setDoAlertReset}
        modalArr={[modal, setModal]}
        alertRef={alertRef}
      />
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
