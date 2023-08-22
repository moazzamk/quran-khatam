import { green, grey } from '@mui/material/colors';

export default {
  background: grey[50],
  marginTop: 2,
  // borderCollapse: 'separate',
  // overflow: 'hidden',
  borderRadius: 1,
  '& .MuiTable-root': {
  },
  '& .MuiTableCell-root': {
    // background: green[50],
    '& td': {
      border: '0px solid transparent',
    }
  },
  '& thead': {
    background: grey[100],
    borderBottom: '2px solid' + green['A400'],
  },
  '& th': {
    fontWeight: '700',
    color: green[800]
  },

}