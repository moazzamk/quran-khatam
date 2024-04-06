import { useEffect, useState } from 'react';
import { Modal, Box, Typography, Stack, Divider } from '@mui/material';
import IconButton from '@mui/material/IconButton';
import HighlightOffIcon from '@mui/icons-material/HighlightOff';
import { green, red, orange } from '@mui/material/colors';

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
