import { useEffect, useState } from 'react';
import { Modal, Box, Alert, Typography } from '@mui/material';
import { green } from '@mui/material/colors';

export default function ModalAlert ({ msg, severity, showModal, setShowModal, children }) {
  const backdrop = document.querySelector('MuiBackdrop-root');
  
  function handleToggle() {
    setShowModal(!showModal);
  }

  return (
    <Modal
      open={showModal}
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
        background: green['50'],
        borderRadius: 2,
        outline: "none"
      }}>
        <Alert severity={ severity }>
          <Typography color={green[800]} variant="subtitle2" component="h6">
            { msg }
          </Typography>
        </Alert>
        { children }
      </Box>
    </Modal>
  );
}
