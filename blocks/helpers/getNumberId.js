/**
 * This function gets the structured id from gsmtc-forms blocks, 
 * something lik this: "gsmtc-forms-form-12345" or "gsmtc-forms-text-5678" or
 *                      "gsmtc-forms-email-38495" etc...
 * And gets the number part of the string returning it.
 * if is undefined or there is somthing wrong it returns "0" 
 * 
 * @returns number as string.
 *
 */

export function getNumberId(id) {

    if ((id !== 'undefined') && (id !== undefined) && typeof id === 'string'){
        let parts = id.split('-');
        if (parts[3] !== undefined){
            return parts[3];
        }
    } 
    return '0';
}