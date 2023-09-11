import { render, useState, useEffect } from '@wordpress/element';
import {
  Paper, Chip
} from '@mui/material';
import { DataGrid, GridToolbarQuickFilter, gridClasses } from '@mui/x-data-grid';
import { orange, green, grey } from '@mui/material/colors';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 
import UnfoldMoreIcon from "@mui/icons-material/UnfoldMore";

import styles from './main.css';
import icons from '../../icons';
const theme = createTheme({
  typography: {
    allVariants: {
      fontFamily: "'Open Sans', Roboto, sans-serif",
    },
  },
});

function UnsortedIcon () {
  return <UnfoldMoreIcon className="icon" />
}

function KhatamTable () {
  const [khatamUsers, setKhatamUsers] = useState([]);

  async function getKhatamUsers () {
    const res = await fetch(kh_auth_rest.currentKhatam, {
      method: 'GET',
    });

    res.json().then(data => {
      console.log(data);
      if (+data.status === 2) {
        let shapedArr = data.data.map(u => {
          u.status = u.status.includes('0') ? 'in progress' : 'completed';
          return u;
        });
  
        for (let i = (30 - (30 - data.data.length) + 1); i <= 30; i++) {
          shapedArr.push({email: null, status: null, juz: i, firstName: null, lastName: null})
        }
        setKhatamUsers(shapedArr);
      }
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
      flex: .3, 
    },
    { 
      field: 'fullName', 
      headerName: 'Reciter', 
      description: 'This column has a value getter and is not sortable.', 
      flex: .4, 
      // width: 100,
      valueGetter: (params) => `${params.row.firstName || ''} ${params.row.lastName || ''}` 
    },
    { 
      field: 'status', 
      headerName: 'Status', 
      flex: .3,
      renderCell: (params) => {
        return (
          params.row.status &&
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
              pageSize: 30,
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
        slots={{ 
          toolbar: QuickSearchToolbar,
          columnUnsortedIcon: UnsortedIcon,        
        }}
        slotProps={{
          toolbar: {
            showQuickFilter: true,
          },
        }}
        pageSizeOptions={[30]}
        getRowId={(row) => +row.juz}
        autoHeight
        sx={{ 
          background: grey[50],
          textTransform: 'capitalize',
          [`& .${gridClasses.row}.even`]: {
            background: grey[100],
          },
          '& .MuiDataGrid-columnHeaders': {
            background: grey['300'],
            borderRadius: 0,
            '& div': {
              fontWeight: '700',
            }
          },
          '.MuiDataGrid-iconButtonContainer': {
            visibility: 'visible',
          },
          '.MuiDataGrid-sortIcon': {
            opacity: 'inherit !important',
          },
        }}
        getRowClassName={(params) =>
          params.indexRelativeToCurrentPage % 2 === 0 ? 'even' : 'odd'
        }
        disableColumnMenu
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
