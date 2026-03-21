import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {

    const { showName, nameLabel, showEmail, emailLabel, showTitle, title, formId, buttonLabel, redirectUrl } = attributes;
    console.log ('useBlockProps :',useBlockProps);

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
                    <ToggleControl
                        label="Show Name"
                        checked={showName}
                        onChange={(value) => setAttributes({ showName: value })}
                    />
                    <TextControl
                        label="Name Label"
                        value={nameLabel}
                        onChange={(value) => setAttributes({ nameLabel: value })}
                    />
                    <ToggleControl
                        label="Show Email"
                        checked={showEmail}
                        onChange={(value) => setAttributes({ showEmail: value })}
                    />
                    <TextControl
                        label="Email Label"
                        value={emailLabel}
                        onChange={(value) => setAttributes({ emailLabel: value })}
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
                <label className='gesimatic-form__label'></label>
                <input type="text" className='gesimatic-form__input'/>
                {showName && <p>Name field enabled</p>}
                <label className='gesimatic-form__label'></label>
                <input type="email" className='gesimatic-form__input'/>
                {showEmail && <p>Email field enabled</p>}
                <button type="button" className='gesimatic-form__button'>{buttonLabel}</button>
            </div>
        </>
    );
}