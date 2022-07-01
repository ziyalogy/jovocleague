jQuery(document).ready(function($) {

    //check dismiss message 
    var Joomla = window.Joomla || {};
    if(Joomla.version == 3){
        /**
         * Render messages send via JSON
         * Used by some javascripts such as validate.js
         * PLEASE NOTE: do NOT use user supplied input in messages as potential HTML markup is NOT sanitized!
         *
         * @param   {object}  messages    JavaScript object containing the messages to render. Example:
         *                              var messages = {
         *                                  "message": ["Message one", "Message two"],
         *                                  "error": ["Error one", "Error two"]
         *                              };
         * @return  {void}
         */
        Joomla.renderMessages = function( messages ) {
            Joomla.removeMessages();

            var messageContainer = document.getElementById( 'system-message-container' ),
                type, typeMessages, messagesBox, title, titleWrapper, i, messageWrapper, alertClass;

            for ( type in messages ) {
                if ( !messages.hasOwnProperty( type ) ) { continue; }
                // Array of messages of this type
                typeMessages = messages[ type ];

                // Create the alert box
                messagesBox = document.createElement( 'div' );

                // Message class
                alertClass = (type === 'notice') ? 'alert-info' : 'alert-' + type;
                alertClass = (type === 'message') ? 'alert-success' : alertClass;
                alertClass = (type === 'error') ? 'alert-error alert-danger' : alertClass;

                messagesBox.className = 'alert ' + alertClass;

                // Close button
                var buttonWrapper = document.createElement( 'button' );
                buttonWrapper.setAttribute('type', 'button');
                buttonWrapper.setAttribute('data-bs-dismiss', 'alert');
                buttonWrapper.className = 'close';
                buttonWrapper.innerHTML = '×';
                messagesBox.appendChild( buttonWrapper );

                // Title
                title = Joomla.JText._( type );

                // Skip titles with untranslated strings
                if ( typeof title != 'undefined' ) {
                    titleWrapper = document.createElement( 'h4' );
                    titleWrapper.className = 'alert-heading';
                    titleWrapper.innerHTML = Joomla.JText._( type );
                    messagesBox.appendChild( titleWrapper );
                }

                // Add messages to the message box
                for ( i = typeMessages.length - 1; i >= 0; i-- ) {
                    messageWrapper = document.createElement( 'div' );
                    messageWrapper.innerHTML = typeMessages[ i ];
                    messagesBox.appendChild( messageWrapper );
                }

                messageContainer.appendChild( messagesBox );
            }
        };
    }

    jQuery(document).on("scroll", onScroll);
    var onepageNavLinks = jQuery('.onepage .t4-navbar .nav-link');

    function onScroll(event){
        var scrollPos = jQuery(document).scrollTop();
        onepageNavLinks.each(function () {
            var currLink = jQuery(this);
            var refElement = jQuery(currLink.attr("href"));
            if (refElement.position().top <= scrollPos /* && refElement.position().top + refElement.height() > scrollPos*/ ) {
                onepageNavLinks.removeClass("active");
                currLink.addClass("active");
            }
            else{
                currLink.removeClass("active");
            }
        });
    }

    // Isotope
    var $container = $('.blog-items .items-intro');
            
    if (!$container.length) return ;
    
    $container.isotope({
        itemSelector: '.item-wrap',
        gutter: 24
    });
    
    // re-order when images loaded
    $container.imagesLoaded(function(){
        $container.isotope();
        
        /* fix for IE-8 */
        setTimeout (function() {
            $('.blog-items .items-intro').isotope();
        }, 8000);  
    });

})