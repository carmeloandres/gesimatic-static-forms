import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {

    const { showName, showEmail, formId, redirectUrl } = attributes;

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
                        label="Show Name"
                        checked={showName}
                        onChange={(value) => setAttributes({ showName: value })}
                    />

                    <ToggleControl
                        label="Show Email"
                        checked={showEmail}
                        onChange={(value) => setAttributes({ showEmail: value })}
                    />

                    <TextControl
                        label="Redirect URL"
                        value={redirectUrl}
                        onChange={(value) => setAttributes({ redirectUrl: value })}
                    />

                </PanelBody>
            </InspectorControls>

            <div {...useBlockProps()}>
                <p><strong>Member Register Form</strong></p>
                {showName && <p>Name field enabled</p>}
                {showEmail && <p>Email field enabled</p>}
            </div>
        </>
    );
}