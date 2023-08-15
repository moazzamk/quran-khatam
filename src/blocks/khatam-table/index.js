import { registerBlockType } from '@wordpress/blocks';
import block from './block.json';
import icons from '../../icons';
import './main.css';

import { Box, Table, TableHead , TableCell, TableRow, TableBody } from '@mui/material';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 
import { grey, red } from '@mui/material/colors';
import { Stack } from '@mui/system';

const rows = [
  { id: 1, juz: 1, reciter: 'John Doe', status: 'Completed' },
  { id: 2, juz: 2, reciter: 'Jane Jackson', status: 'In Progress' },
  { id: 3, juz: 3, reciter: 'John Jay', status: 'Completed' },
  { id: 4, juz: 4, reciter: 'Jamie Jordan', status: 'Completed' },
  { id: 5, juz: 5, reciter: 'Judy Jetson', status: 'Completed' },
  { id: 6, juz: 6, reciter: 'Jack Jester', status: 'Completed' },
  { id: 7, juz: 7, reciter: 'James Judas', status: 'In Progress' },
  { id: 8, juz: 8, reciter: 'Jerome Judas', status: 'Completed' },
  { id: 9, juz: 9, reciter: 'Jill Johnson', status: 'Completed' },
];

const theme = createTheme({
  typography: {
    allVariants: {
      fontFamily: "'Open Sans', Roboto, sans-serif",
    },
  },
});

registerBlockType(block.name, {
  icon: icons.logo,
  edit({ attributes, setAttributes }) {
    
    return (<>
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
								rows.map(row =>
									<TableRow>
										<TableCell>{ row.juz }</TableCell>
										<TableCell>{ row.reciter }</TableCell>
										<TableCell>{ row.status }</TableCell>
									</TableRow>
								)
							}
						</TableBody>
					</Table>
				</Box>
			</ThemeProvider>
			
    </>);
  },
});
