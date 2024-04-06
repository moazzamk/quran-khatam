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
		// const { formBgColor, textFieldBgColor, submitBgColor } = attributes;
    
    return (<>
			<section id="kh-editor-form">
				<h2>Khatam Recitation Form</h2>
			</section>
			
    </>);
  },
});
