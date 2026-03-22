import { __ }from '@wordpress/i18n';
import { useEffect, useState } from "@wordpress/element";
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl, TextControl } from '@wordpress/components';
import { getNumberId } from '../../helpers';

export default function Edit({ attributes, setAttributes }) {

    const { nameLabel, emailLabel, showTitle, title, formId, userRole, buttonLabel, redirectUrl } = attributes;

    const [availableRoles, setAvailableRoles] = useState([{label: 'subscriber', value: 'subscriber'}])

    useEffect(() => {
        if (getNumberId == '0'){
            let segundos = Math.trunc(Date.now() / 1000);
			setAttributes({formId: 'gesimatic-static-forms-'+segundos.toString()})
        }

        setAvailableRoles(Object.entries(gesimaticRoles).map(([value, label]) => ({label,value})));
            
    },[])

    return (
        <>
            <InspectorControls>
                <PanelBody title="Form Settings">

                    <TextControl
                        label="Form ID"
                        value={formId}
                        onChange={(value) => setAttributes({ formId: value })}
                    />
                    <ToggleControl
                        label="Show Title"
                        checked={showTitle}
                        onChange={(value) => setAttributes({ showTitle: value })}
                    />
                    <TextControl
                        label="Form title"
                        value={title}
                        onChange={(value) => setAttributes({ title: value })}
                    />
                    <TextControl
                        label="Name Label"
                        value={nameLabel}
                        onChange={(value) => setAttributes({ nameLabel: value })}
                    />
                    <TextControl
                        label="Email Label"
                        value={emailLabel}
                        onChange={(value) => setAttributes({ emailLabel: value })}
                    />
                    <SelectControl
                        label={ __( 'Select user role', 'gesimatic-static-forms' ) }
                        value={ userRole }
                        options={ [...availableRoles] }
                        onChange={ ( newValue ) => setAttributes( { userRole: newValue } ) }
                        help={ __( 'Select the user role used at registrarion.', 'gesimatic-static-forms' ) }
                    />                   
                    <TextControl
                        label="Button Label"
                        value={buttonLabel}
                        onChange={(value) => setAttributes({ buttonLabel: value })}
                    />
                    <TextControl
                        label="Redirect URL"
                        value={redirectUrl}
                        onChange={(value) => setAttributes({ redirectUrl: value })}
                    />

                </PanelBody>
            </InspectorControls>

            <div {...useBlockProps()}>
                { showTitle && <h2 className='gesimatic-form__title'>{title}</h2> }
                <label className='gesimatic-form__label'>{nameLabel}</label>
                <input type="text" className='gesimatic-form__input'/>
                <label className='gesimatic-form__label'>{emailLabel}</label>
                <input type="email" className='gesimatic-form__input'/>
                <button type="button" className='gesimatic-form__button'>{buttonLabel}</button>
            </div>
        </>
    );
}