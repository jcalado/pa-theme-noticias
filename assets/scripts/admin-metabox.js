class AdminMetabox {

    /**
     * Constructor
     * @NOTE: This class is based on Text of Taxonomy term, so, if you change the text, please update the constructor
     */
    constructor() {
        window.onload=()=> {
            this.metaBox            = document.querySelector('[data-name="embed_url"]').parentElement.parentElement;
            this.visibility         = {'hidden': 'postbox acf-postbox hidden', 'visible':'postbox acf-postbox show'};
            this.terms              = ['Áudio', 'Audio', 'Vídeo', 'Video'];
            this.taxonomyName       = 'xtt-pa-format';
            this.needsValidation    = false;

            this.init();
        }
    }

    /**
     * Initialize MetaBox
     */
    init() {
        this.firstIteration();
        this.getCurrentTerm();
    }

    /**
     * Toggle MetaBox Visibility
     */
    toggleMetaBox(visibility = this.visibility.hidden) {
        // Does it need to be validated in the future?
        visibility[0] === 'hidden' ? this.needsValidation = false : this.needsValidation = true;

        // Set visiblity on Metabox DOM element
        this.metaBox.className = this.visibility[visibility];
    }

    /**
     * Check term value and set visibility only on first Iteration
     * This is wrapped in a setTimeout, so that will be executed delayed
     */
    firstIteration() {
        // Hidden default
        this.toggleMetaBox(['hidden']);

        setTimeout(() => {
            this.getCurrentTerm().then(elem => {
                // If it's video and audio type, so, show it.
                if(this.terms.includes(elem.name)) {
                    this.toggleMetaBox(['visible']);
                }
            });

            // Trigger the watches
            this.watchChanges();
            this.watchUrlChange();
            this.beforeSubmit();
        }, 3000);
    }

    /**
     * Get current selected term of taxonomy
     * Async function
     */
    async getCurrentTerm(){
        let Termid = wp.data.select("core/editor").getCurrentPostAttribute(this.taxonomyName);
        while ( !wp.data.select('core').getEntityRecord('taxonomy', 'xtt-pa-format', Termid) ) {
            await new Promise(r => setTimeout(r, 100));
        }
        return wp.data.select('core').getEntityRecord('taxonomy', 'xtt-pa-format', Termid);
    }

    /**
     * Watch for changes on select option
     * This is the most important function to be called
     */
    watchChanges() {
        // Add a listener on document changes
        document.addEventListener('change', (e) => {
            // If the user clicked on a select and changed its value
            if(e.target.nodeName === 'SELECT'){



               
                let newValue = e.target.querySelector('option:checked').text;

                console.log(newValue);


                // When the new value is selected and that value is audio or video then...
                if(this.terms.includes(newValue)){
                    this.toggleMetaBox(['visible']);
                } else {
                    this.toggleMetaBox(['hidden']);
                    this.clearFields();
                }
            }
        }, false);
    }

    /**
     * Watch for changes URL
     */
    watchUrlChange() {
        // Add a listener
        this.metaBox
            .querySelector('.input-search')
            .addEventListener('change', (e) =>
            {
                // If the URL has been modified, validate it again
                if(this.isValidateFields())
                    this.needsValidation = false;
            });
    }

    /**
     * Clear all ACF embed fields
     */
    clearFields() {
        // Take all inputs and clean it
        let inputs = this.metaBox.querySelectorAll('input');
        inputs.forEach(function(item, index){
            item.value = '';
        });

        // Take the canvas and clean it
        let canvas = this.metaBox.querySelector('.canvas-media');
        canvas.innerHTML = '';

        // Some style fixes
        this.metaBox.querySelector('i.acf-icon').style.display = 'block';
        this.metaBox.querySelector('.canvas').style.minHeight = '250px';
    }

    /**
     * Validate MetaBox Fields
     */
    isValidateFields() {
        let url = this.metaBox.querySelector('.input-search').value;

        // If the URL exists, if it is not empty and is a valid URL, then...
        if(url && url !== '' && this.isValidURL(url))
            return true;

        return false;
    }

    /**
     * Validate URL
     */
    isValidURL(string) {
        // Regex to URL validation
        let test = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
        return (test !== null)
    }

    /**
     * Show a message on wp-editor
     */
    showErrorMessage(){
        // A Error Message hook
        wp.data.dispatch( 'core/notices' )
            .createErrorNotice(
                'Link de Áudio ou Vídeo é campo Obrigatório para esse formato de post',
                { id: 'URL_NOTICE',isDismissible: true}
            );
    }

    /**
     * Dismiss all message on wp-editor
     */
    dissmissMessage(){
        // Dismiss the error message hook
        wp.data.dispatch( 'core/notices' ).removeNotice('URL_NOTICE');
    }

    /**
     * Lock post save
     */
    lockPostSaving(){
        // Lock save hook post
        wp.data.dispatch( 'core/editor' ).lockPostSaving( 'lock_url' );
    }

    /**
     * Unlock post save
     */
    unlockPostSaving(){
        // Unlock save hook post
        wp.data.dispatch( 'core/editor' ).unlockPostSaving( 'lock_url' );
    }

    /**
     * Stop submitting MetaBox when clicking on Publish or update button
     */
    beforeSubmit() {
        // Get submit button
        const btnSubmit = document.querySelector('.edit-post-header__settings')
            .querySelector('button.is-primary');

        // Add a Listener
        btnSubmit.addEventListener('click', (e) => {
            // Stop default functions
            e.preventDefault();

            // If you need validation and it is not validated, then...
            if(this.needsValidation && !this.isValidateFields()) {
                this.showErrorMessage();
                this.lockPostSaving();
            } else {
                // If it's validated or doesn't need validation then clean everything and let it go
                this.dissmissMessage();
                this.unlockPostSaving();
                return true;
            }
        });
    }
}

new AdminMetabox();
