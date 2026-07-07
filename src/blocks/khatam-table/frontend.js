import { createRoot, useState, useEffect, useRef } from '@wordpress/element';
import {
  Paper, Chip
} from '@mui/material';
import { DataGrid, GridToolbarQuickFilter, gridClasses } from '@mui/x-data-grid';
import { orange, green, grey } from '@mui/material/colors';

import { ThemeProvider, createTheme } from '@mui/material/styles'; 
import CircularProgress from '@mui/material/CircularProgress';

import styles from './main.css';

const theme = createTheme({
  typography: {
    allVariants: {
      fontFamily: "'Open Sans', Roboto, sans-serif",
    },
  },
});

function UnsortedIcon () {
  return (
    <svg className="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
      <path d="M12 5.83 15.17 9l1.41-1.41L12 3 7.41 7.59 8.83 9 12 5.83zm0 12.34L8.83 15l-1.41 1.41L12 21l4.59-4.59L15.17 15 12 18.17z"/>
    </svg>
  );
}

function KhatamTable () {
  const [khatamUsers, setKhatamUsers] = useState([]);
  const [isDataAvailable, setIsDataAvailable] = useState(false);
  const [highlightedJuzs, setHighlightedJuzs] = useState([]);
  const prevUsersRef = useRef([]);
  const isFirstLoad = useRef(true);

  async function getKhatamUsers () {
    setIsDataAvailable(false);
    const res = await fetch(kh_auth_rest.currentKhatam, {
      method: 'GET',
    });

    res.json().then(data => {
      if (+data.status === 2) {
        let shapedArr = data.data.map(u => {
          u.status = u.status.includes('0') ? 'in progress' : 'completed';
          return u;
        });
  
        for (let i = (30 - (30 - data.data.length) + 1); i <= 30; i++) {
          shapedArr.push({email: null, status: null, juz: i, firstName: null, lastName: null})
        }

        // Detect changes for flash highlight
        if (!isFirstLoad.current) {
          const prev = prevUsersRef.current;
          const changedJuzs = [];

          shapedArr.forEach(user => {
            if (!user.email) return;
            const prevUser = prev.find(p => p.juz === user.juz);
            // New user in this slot or status changed
            if (!prevUser || !prevUser.email || prevUser.email !== user.email || prevUser.status !== user.status) {
              changedJuzs.push(+user.juz);
            }
          });

          if (changedJuzs.length > 0) {
            setHighlightedJuzs(changedJuzs);
            // Clear highlight after animation
            setTimeout(() => setHighlightedJuzs([]), 3000);
          }
        }

        isFirstLoad.current = false;
        prevUsersRef.current = shapedArr;
        setKhatamUsers(shapedArr);
      }
      setIsDataAvailable(true);
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
      type: 'number', 
      flex: .3,
      headerAlign: 'left',
      align: 'left',
    },
    { 
      field: 'fullName', 
      headerName: 'Reciter', 
      description: 'This column has a value getter and is not sortable.', 
      flex: .4, 
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
        sx={{
          display: "flex",
          alignItems: "center",
          justifyContent: "center"
        }}
      >

      {
        !isDataAvailable ?
        <CircularProgress sx={{ padding: "24px", }} /> :
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
            [`& .${gridClasses.row}.kh-flash`]: {
              animation: 'kh-row-flash 3s ease-out',
            },
            '@keyframes kh-row-flash': {
              '0%': { background: '#bbf7d0' },
              '30%': { background: '#bbf7d0' },
              '100%': { background: 'inherit' },
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
          getRowClassName={(params) => {
            const classes = [];
            classes.push(params.indexRelativeToCurrentPage % 2 === 0 ? 'even' : 'odd');
            if (highlightedJuzs.includes(+params.row.juz)) {
              classes.push('kh-flash');
            }
            return classes.join(' ');
          }}
          disableColumnMenu
        />
      }
      </Paper>
    </ThemeProvider>
  );
}

document.addEventListener('DOMContentLoaded', () => {
  let block = document.querySelector('#kh-table-container');
  if (!block) return;

  const root = createRoot(block);
  root.render(<KhatamTable />);
});
