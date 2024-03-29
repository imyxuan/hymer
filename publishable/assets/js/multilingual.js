;( function( $, window, document, undefined ) {

    "use strict"

    const pluginName = "multilingual",
        defaults = {
            editing: false,                       // Editing or View
            form: '.form-edit-add',
            transInputs: 'input[data-i18n = true]', // Hidden inputs holding translations
            langSelectors: '.language-selector:first input' // Language selector inputs
        }

    function Plugin ( element, options ) {
        this.element   = $(element)
        this.settings  = $.extend( {}, defaults, options )
        this._defaults = defaults
        this._name     = pluginName
        this.init()
    }

    $.extend( Plugin.prototype, {
        init: function() {
            this.form          = this.element.find(this.settings.form)
            this.transInputs   = $(this.settings.transInputs)
            this.langSelectors = this.element.find(this.settings.langSelectors)

            if (this.transInputs.length === 0 || this.langSelectors === 0) {
                return false
            }

            this.setup()
            this.refresh()
        },


        setup: function() {
            const _this = this;

            this.locale = this.returnLocale()

            $('.js-language-label').text(this.locale)

            /**
             * Setup language selector
             */
            this.langSelectors.change(e => {
                _this.selectLanguage(e)
            })

            /**
             * Save data before submit
             */
            if (this.settings.editing) {
                $(this.form[0]).on('submit', function(e) {
                    _this.prepareData()
                })
            }
        },

        /**
         * Refresh plugin data, required for dynamic calls (ex menu)
         */
        refresh: function() {
            const _this = this

            /**
             * Setup translatable inputs
             */
            this.transInputs.each(function(i, inp) {
                const _inp = $(inp),
                    inpUsr = _inp.nextAll(_this.settings.editing ? '.form-control' : '')

                inpUsr.data("inp", _inp)
                _inp.data("inpUsr", inpUsr)

                // Load and Save data in hidden input
                const $_data = _this.loadJsonField(_inp.val())
                if (_this.settings.editing) {
                    _inp.val(JSON.stringify($_data))
                }

                _this.langSelectors.each(function(i, btn) {
                    _inp.data(btn.id, $_data[btn.id])   // Save translation in mem
                    if (btn.id === _this.locale) {
                        _this.loadLang(_inp, btn.id)    // Load active locale
                    }
                })
            })
        },

        loadJsonField: function(str) {
            let $_data = {};

            if (this.isJsonValid(str)) {
                $_data = JSON.parse(str)

                /**
                 * Convert nulls to ''.
                 */
                this.langSelectors.each(function(i, btn) {  // loop languages
                    $_data[btn.id] = $_data[btn.id] || ''
                })

                return $_data
            }

            /**
             * For the sake of validation, this looks ugly, but it will work
             */
            this.langSelectors.each(function(i, btn) {
                $_data[btn.id] = ''
            })

            return $_data
        },


        isJsonValid: function(str) {
            try {
                JSON.parse(str)
            } catch (ex) {
                return false
            }
            return true
        },

        /**
         * Return Locale for a given Button Group Selector
         *
         * @return string The locale.
         */
        returnLocale: function() {
            return this.langSelectors.filter(function() {
                return $(this).is(':checked')
            }).prop('id')
        },

        selectLanguage: function(e) {
            const _this = this,
                lang = e.target.id;

            this.transInputs.each(function(i, inp) {
                if (_this.settings.editing) {
                    _this.updateInputCache($(inp))
                }
                _this.loadLang($(inp), lang)
            });

            this.locale = lang

            $('.js-language-label').text(lang)
        },

        /**
         * Update cache for all inputs, and prepare form data for submit
         */
        prepareData: function() {
            const _this = this;
            this.transInputs.each(function(i, inp) {
                _this.updateInputCache($(inp))
            })
        },

        /**
         * Update cache for a single input
         */
        updateInputCache: function(inp) {
            let _this = this,
                inpUsr = inp.data('inpUsr'),
                $_val = $(inpUsr).val(),
                $_data = {}  // Create new data

            if (inpUsr.hasClass('richTextBox')) {
                const $_mce = tinymce.get('richtext' + inpUsr.prop('name'));
                $_val = $_mce.getContent()
            }

            if (inpUsr.hasClass('easymde')) {
                const $codemirror = inpUsr.nextAll('.CodeMirror')[0].CodeMirror;
                $_val = $codemirror.getDoc().getValue()
                $codemirror.save()
            }

            this.langSelectors.each(function(i, btn) {
                const lang = btn.id;
                $_data[lang] = (_this.locale === lang) ? $_val : inp.data(lang)
            })

            inp.val(JSON.stringify($_data))
            inp.data(this.locale, $_val)     // Update single key Mem
        },

        /**
         * Load input translation
         */
        loadLang: function(inp, lang) {
            const inpUsr = inp.data("inpUsr"),
                _val = inp.data(lang);

            if (!this.settings.editing) {
                inpUsr.text(_val)

            } else {
                const _mce = tinymce.get('richtext' + inpUsr.prop('name'));
                if (inpUsr.hasClass('richTextBox') && _mce && _mce.initialized) {
                    _mce.setContent(_val)
                } else {
                    inpUsr.val(_val)
                    if (inpUsr.hasClass('easymde')) {
                        var $codemirror = inpUsr.nextAll('.CodeMirror')[0].CodeMirror
                        $codemirror.getDoc().setValue(inpUsr.val())
                    }
                }
            }
        }
    })

    $.fn[ pluginName ] = function( options ) {
        return this.each( function() {
            if ( !$.data( this, pluginName ) ) {
                $.data( this, pluginName, new Plugin(this, options) )
            }
        } )
    }

} )( jQuery, window, document )
