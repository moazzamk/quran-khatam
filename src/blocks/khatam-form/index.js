import { registerBlockType } from '@wordpress/blocks';
import { 
	useBlockProps, 
	InspectorControls,
	PanelColorSettings
} from '@wordpress/block-editor';
import { PanelBody, ColorPalette } from '@wordpress/components';
import { __ } from '@wordpress/i18n'
import block from './block.json';
import icons from '../../icons';
import './main.css';

import {
	Button,
	Card, 
	CardContent, 
	CardHeader,
	CardActions, 
	Divider, 
	Typography,
	FormControl,
	FormLabel,
	FormControlLabel,
	RadioGroup,
	Radio,
	TextField
} from '@mui/material';
import { ThemeProvider, createTheme } from '@mui/material/styles'; 
import { grey, red } from '@mui/material/colors';
import { Stack } from '@mui/system';

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
		// const { formBgColor, textFieldBgColor, submitBgColor } = attributes;
    
    return (<>
			<ThemeProvider theme={ theme }>
				<Card elevation={2} sx={{ backgroundColor: grey[50]}}>
					<CardHeader 
						title="Khatam Recitation Form"
						openIcon={icons.logo}
						color="secondary"
					/>
					<Divider variant="middle" />
					<form>
						<CardContent sx={{ paddingLeft: "2rem"}}>
							<Stack spacing={2}>
								<FormControl>
									<FormLabel id="kh-form-type" required>
										<Typography variant="body2">
											Please Select One
										</Typography>
									</FormLabel>
									<RadioGroup 
										aria-labelledby='kh-form-type' 
										defaultValue="I want to recite a juz"
										name="khFormType"
									>
										<FormControlLabel value="reciteJuz" control={<Radio />} label="I want to recite a juz" />
										<FormControlLabel value="juzCompleted" control={<Radio />} label="I have completed a juz" />
									</RadioGroup>
								</FormControl>
								<FormControl>
									<TextField id="khName" label="Name(s)" variant="standard" helperText="Must be comma separated" required />
								</FormControl>
								<FormControl>
									<TextField id="khEmail" label="Email" variant="outlined" required />
								</FormControl>
							</Stack>

						</CardContent>
						<CardActions sx={{justifyContent: "flex-end"}}>
							<Button color="secondary" variant="contained">
								<Typography variant="subtitle2" component="span">
									Submit
								</Typography>
							</Button>
						</CardActions>
					</form>
				</Card>
			</ThemeProvider>
			
    </>);
  },
});
