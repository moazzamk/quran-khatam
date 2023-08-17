import { render, useState, useEffect } from '@wordpress/element';
import {
  Box,
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableRow,
  Chip,
} from '@mui/material';
import { red, blue, green } from '@mui/material/colors';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 

const theme = createTheme({
  // palette: {
  //   primary: blue,
  //   warning: red,
  // },
  typography: {
    allVariants: {
      fontFamily: "'Open Sans', Roboto, sans-serif",
    },
  },
});

import icons from '../../icons';

function KhatamTable () {
  const [khatamUsers, setKhatamUsers] = useState([]);

  async function getKhatamUsers () {
    const res = await fetch(kh_auth_rest.currentKhatam, {
      method: 'GET',
    });

    res.json().then(data => setKhatamUsers([...data.data]));
  }

  useEffect(
    () => {
      let block = document.querySelector('#kh-table-container');
      block.addEventListener('khatamUpdated', () => {
        getKhatamUsers();
      });
  
      getKhatamUsers();
    }
    , []
  );

  return (
    <ThemeProvider theme={ theme }>
      <Box sx={{ border: '1px dashed grey' }}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Juz</TableCell>
              <TableCell>Reciter</TableCell>
              <TableCell>Status</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {
              khatamUsers.map(row =>
                <TableRow>
                  <TableCell>{ row.juz }</TableCell>
                  <TableCell>{ `${row.firstName} ${row.lastName}` }</TableCell>
                  <TableCell>
                    <Chip 
                    label={+row.status === 0 ? 'In Progress' : 'Completed'}
                    color={+row.status === 0 ? 'warning' : "success" }
                    size="small"
                    />
                  </TableCell>
                </TableRow>
              )
            }
          </TableBody>
        </Table>
      </Box>
    </ThemeProvider>
  );
}


document.addEventListener('DOMContentLoaded', () => {
  let block = document.querySelector('#kh-table-container');

  render(
    <KhatamTable />,
    block
  )
});
