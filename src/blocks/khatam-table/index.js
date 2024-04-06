import { registerBlockType } from '@wordpress/blocks';
import block from './block.json';
import icons from '../../icons';
import './main.css';

import { createTheme } from '@mui/material/styles'; 


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
			<section id="kh-editor-table">
				<h2>Khatam Recitation Table</h2>
				<table>
					<thead>
						<tr>
							<th>Juz</th>
							<th>Reciter</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>John Doe</td>
							<td>Completed</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Jane Doe</td>
							<td>In progress</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Jamie Doe</td>
							<td>In progress</td>
						</tr>
					</tbody>
				</table>
			</section>
			
    </>);
  },
});
