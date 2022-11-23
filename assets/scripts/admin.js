(function(element) {
    const el = element.createElement;

    function customizeTaxonomySelector(OriginalComponent) {
        return function(props) {
            // if(props.slug === 'xtt-pa-format' || props.slug === 'xtt-pa-regiao' || props.slug === 'xtt-pa-press-type')

            if(props.slug === 'xtt-pa-owner' || props.slug === 'xtt-pa-press-type' || props.slug === 'xtt-pa-format') {
                let newprops = {...props};
                if(props.slug === 'xtt-pa-format')
                    newprops.selected = 'noticia';

                return el(window.DropdownTermSelector, newprops);
            }

            return el(OriginalComponent, { ...props });
        };
    }

    wp.hooks.addFilter('editor.PostTaxonomyType', 'adventistas-videos', customizeTaxonomySelector);
}) (
	window.wp.element,
	window.wp.components,
    window.wp.data,
    window.wp.i18n,
    window.lodash,
);
