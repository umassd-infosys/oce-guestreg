/* Manage OCE Guest Registration Carts */
/*
Feb 2020: initial release mpr


 */

var oceGuestRegistration = {
    courseURL: "https://umassd-postgres.herokuapp.com/oce-guestreg/cart.php",
    checkoutURL: "https://www.umassd.edu/online/courses/registration",
    cart: {courses:[],n:0},
    maxCourses: false,
    crns: [],
    htmlStub: '<div class="oce-guestreg-cart" style="\'display:none"> <div class="oce-guestreg-cart-contents"> You have <span class="oce-guestreg-cart-contents-number"></span> course<span class="oce-guestreg-cart-contents-plural">s</span> in your cart: <div class="oce-guestreg-cart-contents-list"> </div> </div><div class="oce-guestreg-cart-checkout"> <a class="btn btn-auto-icon fa-shopping-cart btn-success oce-checkout btn-lg oce-guestreg-cart-checkout-link" href="https://www.umassd.edu/online/courses/registration"> Check Out </a> </div></div>',
    addCourse: function(courseId) {
        if (oceGuestRegistration.maxCourses && oceGuestRegistration.cart.courses.length >= oceGuestRegistration.maxCourses) {
            var b = 'You may only register for ' + oceGuestRegistration.maxCourses + ' per transaction!';
            alert(b);
            return false;
        } else {
            try {
                console.log(oceGuestRegistration.courseURL);
                $.getJSON(oceGuestRegistration.courseURL + '?callback=?&add=' + courseId, function (jsonData) {
                    oceGuestRegistration.cart = jsonData;
                    oceGuestRegistration.render();
                    if (jsonData.error) {
                        alert(jsonData.error);
                        return false;
                    }
                });
            } catch (ex) {
                console.log('Error with addCourse');
                console.log(ex);
            }
        }

    },
    dumpCart: function() {
      $.getJSON(oceGuestRegistration.courseURL+'?callback=?&empty=true', function(jsonData) {
          oceGuestRegistration.cart = jsonData;
          oceGuestRegistration.render();
      });
    },
    removeCourse: function(courseId) {
        try {
            $.getJSON(oceGuestRegistration.courseURL+'?callback=?&remove='+courseId, function(jsonData) {
                oceGuestRegistration.cart = jsonData;
                oceGuestRegistration.render();

            });
        } catch(ex) {
            console.log('Error with removeCourse');
            console.log(ex);
        }

    },
    render: function() {
        /* Hide checkout if nothing is in the cart */
        var baseEl = '.oce-guestreg-cart',
            checkoutEl = '.oce-guestreg-cart-checkout',
            listEl = '.oce-guestreg-cart-contents-list',
            countEl = '.oce-guestreg-cart-contents-number',
            html = '';
        oceGuestRegistration.crns = [];
        if(oceGuestRegistration.cart.courses.length==0) {
            $(baseEl).hide();
        } else {
            $(baseEl).show();
            $(checkoutEl).show();
            //If more then one, make gramatically correct
            if(oceGuestRegistration.cart.courses.length==1) {
                $('.oce-guestreg-cart-contents-plural').html('');
            } else {
                $('.oce-guestreg-cart-contents-plural').html('s');
            }
            $(countEl).html(oceGuestRegistration.cart.n);
            //Dynamically build cart list
            for(var n=0; n<oceGuestRegistration.cart.courses.length; n++) {
                var c = oceGuestRegistration.cart.courses[n];
                var crn = c.crn;
                oceGuestRegistration.crns.push(crn);
                var courseTitle = c.crs_description+': '+c.crs_subject+'-'+c.crs_catalog_number+'-'+c.crs_section;
                html += "<div class='list-group-item list-group-item-action' data-crn='"+crn+"' title='Remove from cart'><a href='javascript:oceGuestRegistration.removeCourse(\""+crn+"\");'><span class='fa-li'></span><i class='fa fa-minus-circle'></i> "+courseTitle+"</a></div>";
            }
            $(listEl).html(html);
         }
    },
    getCourses: function(callback) {
        try {
            $.getJSON(oceGuestRegistration.courseURL+'?callback=?',function(jsonData){
                oceGuestRegistration.cart = jsonData;
                if(jsonData.maxCourses) {
                    oceGuestRegistration.maxCourses = jsonData.maxCourses;
                }
                oceGuestRegistration.render();
                if(callback && typeof callback == "function") {
                    callback();
                }
            } );
        } catch(ex) {
            console.log('Error with getCourses');
        }

    },
    parseURL: function(url) {
        var vars = [], hash;
        var hashes = url.slice(url.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split("=");
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    rewriteT4Page: function() {

        //add our HTML if it doesn't already exist here
        if ( $('.oce-guestreg-cart').length==0) {
            $('.registrationControls .btn-group-custom').append(oceGuestRegistration.htmlStub);
        }
        //Rewrite links where appropriate
        $('a.enrollmentTrigger-O').each(function(idx,el) {
            var url = $(this).attr('href');
            if(url && url.indexOf('http') == 0) {
                var urlParts = oceGuestRegistration.parseURL(url);
                if (urlParts && urlParts.rn) {
                    $(this).data('crn', urlParts.rn);
                    $(this).attr('href', 'javascript:oceGuestRegistration.addCourse(\"' + urlParts.rn + '\");');
                    $(this).html('Add to Cart');
                    $(this).removeClass('fa-sign-in');
                    $(this).removeClass('register');
                    $(this).addClass('fa-cart-plus');
                    $(this).attr('target','');
                    var newButton = "",link="";
                    /* Build a new button depending upon the status of our cart */
                    if(oceGuestRegistration.cart.n>0) {
                        //link = 'javascript:oceGuestRegistration.checkout();';
                        link = oceGuestRegistration.checkoutURL;
                        newButton =  '<a class="btn btn-auto-icon fa-shopping-cart btn-success oce-checkout btn-lg" href="'+link+'">Check Out</a>';
                    }
                    $(this).after(newButton);
                }
            }
        });
    }
};
/* On initial document initialization, get the current courses */
$(document).ready(function(){

    oceGuestRegistration.getCourses(oceGuestRegistration.rewriteT4Page());

    /* On Registration Checkout Page */
    $('#oce-guest-checkout-btn').click(function() {
        $('#oce-guest-checkout-iframe').toggle();
    });

});