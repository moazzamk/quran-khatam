import { useEffect, useState } from 'react';
import { Modal, Box, Typography, Stack, Divider } from '@mui/material';
import IconButton from '@mui/material/IconButton';
import { green, red, orange } from '@mui/material/colors';

function HighlightOffIcon(props) {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor" {...props}>
      <path d="M14.59 8 12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
    </svg>
  );
}

export default function ModalAlert ({ modalArr }) {
  const backdrop = document.querySelector('MuiBackdrop-root');
  const [modal, setModal] = modalArr;
  const modalDefaultState = {
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
  };

  useEffect(() => {
    console.log(modal);
  }, [modal]);


  function handleToggle() {
    setModal({...modalDefaultState,
      showModal: !modal.showModal,
    });
  }

  return (<>
    <Modal
      open={ modal.showModal }
      aria-labelledby="modal-modal-title"
      aria-describedby="modal-modal-description"
      onClose={handleToggle}
    >
      <Box sx={{
        position: 'absolute',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        width: 400,
        bgcolor: 'background.paper',
        boxShadow: 4,
        p: 2,
        background: modal.modalStates[modal.severity].background,
        borderRadius: 2,
        outline: "none"
      }}>
        <Stack>
          <Stack 
            direction="row"
            justifyContent="space-between"
            alignItems="center"
          >
            <Typography 
              color={modal.modalStates[modal.severity].fontColor} 
              variant="h5" 
              component="h1"
              sx={{
                paddingLeft: '12px',
                textTransform: 'uppercase'
              }}
            >
              {modal.modalTitle}
            </Typography>
            <div
              onClick={handleToggle}
            >
              <IconButton color={ modal.severity }>
                <HighlightOffIcon />
              </IconButton>
            </div>
          </Stack>
          <Divider color={ modal.severity } />
          <Stack sx={{
            padding: '12px',
            marginTop: '12px'
          }}>
            <Typography 
              color={modal.modalStates[modal.severity].fontColor } 
              variant="subtitle2" 
              component="p"
            >
              { modal.modalText }
            </Typography>
            { modal.children && modal.children.map(el => el) }
          </Stack>
        </Stack> 
      </Box>
    </Modal>
  </>);
}
