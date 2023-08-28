import { render, useState, useEffect, useRef } from '@wordpress/element';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 
import { Alert } from '@mui/material';

import ModalAlert from './components/ModalALert/ModalALert';
import ReciteForm from './components/ReciteForm/ReciteForm';
import SuccessTable from './components/SucessTable/SuccessTable';

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

  const [showModal, setShowModal] = useState(false);
  const [addedUsers, setAddedUsers] = useState([]);

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

  return (
    <ThemeProvider theme={ theme }>
      <ModalAlert 
        msg="The following users were successfully added to current khatam"
        severity="success"
        showModal={showModal}
        setShowModal= {setShowModal}
      >
        <SuccessTable users={addedUsers}/>
      </ModalAlert>

      <Alert severity={alertSev} ref={alertRef} sx={{
        marginBottom: 2,
      }} >{ alertMsg }</Alert>

      <ReciteForm 
        availableSpots={availableSpots} 
        alertMsg = {alertMsg}
        setAlertMsg={setAlertMsg}
        setDoAlertReset={setDoAlertReset}
        setShowModal={setShowModal}
        setAddedUsers={setAddedUsers}
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
