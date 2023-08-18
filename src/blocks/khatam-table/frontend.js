import { render, useState, useEffect } from '@wordpress/element';
import {
  Paper,
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableRow,
  Chip,
  TextField,
} from '@mui/material';
import { orange, green, grey } from '@mui/material/colors';
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
  const [displayUsers, setDisplayUsers] = useState();
  const [tableSearch, setTableSearch] = useState('');

  async function getKhatamUsers () {
    const res = await fetch(kh_auth_rest.currentKhatam, {
      method: 'GET',
    });

    res.json().then(data => {
      setKhatamUsers(data.data.map(u => {
        u.status = u.status.includes('0') ? 'in progress' : 'completed';
        return u;
      }));
    });
  }

  useEffect(
    () => {
      let block = document.querySelector('#kh-table-container');
      block.addEventListener('khatamUpdated', () => {
        getKhatamUsers();
      });
  
      getKhatamUsers();
    },
    []
  );

  useEffect(() => {
    setDisplayUsers(khatamUsers.filter(u => 
      u.firstName.includes(tableSearch) ||
      u.lastName.includes(tableSearch) ||
      u.status.includes(tableSearch) ||
      u.juz == +tableSearch
    ));
  }, [tableSearch]);

  useEffect(() => {
    setDisplayUsers(khatamUsers);
  }, [khatamUsers]);

  return (
    <ThemeProvider theme={ theme }>
      <Paper 
        elevation={2}
        sx={{ 
          background: grey['50']
        }}
      >
        <div className="kh-table-search-container">
          <TextField 
            id="khTableSearch" 
            label="Search" 
            variant="outlined"
            size="small"
            sx={{ 
              width: '96%',
              background: grey['100']
            }}
            value={ tableSearch }
            onChange={e => setTableSearch(e.target.value) }
          />
        </div>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Juz</TableCell>
              <TableCell>Reciter</TableCell>
              <TableCell>Status</TableCell>
            </TableRow>
          </TableHead>
          <TableBody id="kh-recitation-table">
            { displayUsers != null && displayUsers.length > 0 ? 
              (
                displayUsers.map(row =>
                  <TableRow>
                    <TableCell>{ row.juz }</TableCell>
                    <TableCell>{ `${row.firstName} ${row.lastName}` }</TableCell>
                    <TableCell>
                      <Chip 
                      label={row.status}
                      size="small"
                      sx={{ background: row.status == 'completed' ? green['A200'] : orange['200'] }}
                      />
                    </TableCell>
                  </TableRow>
                )
              ) : (
                <TableRow>
                  <TableCell colSpan={3}>No matching records found</TableCell>
                </TableRow>
              )
            }
          </TableBody>
        </Table>
      </Paper>
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
