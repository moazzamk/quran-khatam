import { render, useState, useEffect } from '@wordpress/element';
import {
  Paper, Chip
} from '@mui/material';
import { DataGrid, GridToolbarQuickFilter, } from '@mui/x-data-grid';
import { orange, green, grey } from '@mui/material/colors';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 

const theme = createTheme({
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

    res.json().then(data => {
      console.log(data);
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

  const columns = [
    {
      field: 'juz', 
      headerName: 'Juz', 
      type: 'bumber', 
      flex: .2, 
    },
    { 
      field: 'fullName', 
      headerName: 'Full name', 
      description: 'This column has a value getter and is not sortable.', 
      flex: .6, 
      // width: 100,
      valueGetter: (params) => `${params.row.firstName || ''} ${params.row.lastName || ''}` 
    },
    { 
      field: 'status', 
      headerName: 'Status', 
      flex: .2,
      renderCell: (params) => {
        return (
          <Chip 
            label={ params.row.status }
            sx={{ 
              background: params.row.status == 'completed' ? green['A200'] : orange['A100'],
            }}
            size='small'
          />
        )
      }
    },
  ];

  function QuickSearchToolbar() {
    return (
      <div 
        style={{
          padding: '1rem'
        }}
      >
        <GridToolbarQuickFilter 
          fullWidth
          size='medium'
          variant='outlined'
          sx={{
            background: grey[100],
            width: '100%'
          }}
        />
      </div>
    );
  }

  return (
    <ThemeProvider theme={ theme }>
      <Paper
        elevation={2}
      >
      <DataGrid
        rows={khatamUsers}
        columns={columns}
        initialState={{
          pagination: {
            paginationModel: {
              pageSize: 5,
            },
          },
          filter: {
            khatamUsers,
            filterModel: {
              items: [],
              quickFilterValues: [],
            },
          },
        }}
        disableColumnFilter
        disableColumnSelector
        disableDensitySelector
        slots={{ toolbar: QuickSearchToolbar }}
        slotProps={{
          toolbar: {
            showQuickFilter: true,
          },
        }}
        pageSizeOptions={[5]}
        getRowId={(row) => +row.juz}
        autoHeight
        sx={{ 
          background: grey[50],
          textTransform: 'capitalize'
        }}
      />
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
