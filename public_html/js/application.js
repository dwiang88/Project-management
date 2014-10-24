/**
 *
 *
 * Author: SARUNAS TAMASAUSKAS
 *
 *
 */

$('button[task-finished-data]').click(function() {
     $.post(window.location.pathname+"/"+$(this).attr('task-finished-data')+"/state");
    $('#'+ $(this).attr('task-finished-data')).fadeOut("fast");
    return false;

});
$('button[id="task-finished-indside-data"]').click(function() {
    $.post(window.location.pathname+"/state");
});


$('#task_finished').click(function() {
    $('input[name="finished"]').val('true');
    $('form[id="task_form"]').submit();
    return false;
});
$('#task_unfinished').click(function() {
    $('input[name="finished"]').val('');
    $('form[id="task_form"]').submit();
    return false;
});
/*change sorting data into selected option*/
$('input[data-archived="1"]').prop('checked', true);
$('input[data-visibility="1"]').prop('checked', true);
$('select[data-milestone]').val($('select[data-milestone]').attr('data-milestone')).attr('selected',true);
$('select[data-oder-by]').val($('select[data-oder-by]').attr('data-oder-by')).attr('selected',true);
$('select[data-type]').val($('select[data-type]').attr('data-type')).attr('selected',true);

/*change sorting data into selected option*/

//change color of div depending on highest priority
$('div[data-priority="Highest"],tr[data-priority="Highest"]').css( "background-color", "#F2DEDE" );
//change color of div depending on high priority
$('div[data-priority="High"],tr[data-priority="High"]').css( "background-color", "#FCF8E3" );


$(document).ready(function () {
    //hide edit buttons in main dashboard
    $('a[id="dash_edit"], a[id="dashboard_remove"]').hide();
    
    //delete confirmation
    $('a[data-method]').click(function(){
        var method = $(this).attr('data-method');

        if(method == "delete"){
            if (confirm('Are you sure you want to delete this?')) {
                // Save it!
		$.ajaxSetup({async:false});
                var request = $.ajax({
                    url: window.location.pathname,
                    type: method
                });

            } else {
                return false;
            }
        }

    });

    //delete confirmation for tasks and molestones
    $('a[remove-id-projects]').click(function(){   
        if (confirm('Are you sure you want to delete this?')) {
            // Save it!
		$.ajaxSetup({async:false});
            var request = $.ajax({
                url: window.location.pathname +"/"+ $(this).attr('remove-id-projects'),
                type: "delete"
            });
	
        } else {
            return false;
        }
        
    });

});
//edit mode in main dashboard
$(document).on("click", 'a[href="edit_mode"]', function () {
    //check if edit mode is not on
    if ($('#edit').attr('class') != 'active'){
        //unhide edit buttons
        $('a[id="dash_edit"], a[id="dash_save"], a[remove-id-projects]').show();
        //add active class in drodown menu
        $('#edit').addClass('active');
        //remove class from sort
        $('#sort').removeClass();
        //disable sorting
        sorting("disable");
        //close dropdown
        $('[data-toggle="dropdown"]').parent().removeClass('open');
        return false;
    //if drop down is enabled
    }else {
        //remove acive class from edit mode in dropdown
        $('#edit').removeClass();
        //hide edit buttons
        $('a[id="dash_edit"], a[id="dash_save"], a[remove-id-projects]').hide();
        //close dropdown menu
        $('[data-toggle="dropdown"]').parent().removeClass('open');
        //remove border from editable content
        $('div[id="dashboard_text"]').attr('contenteditable', false).css('border', '');
        $('h4').attr('contenteditable', false).css('border', '');

        //turn of all save icons into edtiable icons
        $('a[id="dash_save"]').html('<i class="icon-pencil pull-right"></i>').attr('id', 'dash_edit');
        return false;

    }
});
//sort mode click on dashboard
$(document).on("click", 'a[href="sort_mode"]', function () {
    //check if mode is not active
    if ($('#sort').attr('class') != 'active'){
        //hide editable icons
        $('a[id="dash_edit"], a[id="dash_save"], a[remove-id-projects]').hide();
        //change class into active
        $('#sort').addClass('active');
        //remove class from edit
        $('#edit').removeClass();
        //remove border from editable content
        $('div[id="dashboard_text"]').attr('contenteditable', false).css('border', '');
        $('h4').attr('contenteditable', false).css('border', '');

        //turn of all save icons into edtiable icons
        $('a[id="dash_save"]').html('<i class="icon-pencil pull-right"></i>').attr('id', 'dash_edit');
        //enable sorting
        sorting();
        //close dropdown
        $('[data-toggle="dropdown"]').parent().removeClass('open');
        return false;
    //if mode is already active
    }else {
        //remove class from sortmode
        $('#sort').removeClass();
        //disable sorting mode
        sorting("disable");
        //close dropdown menu
        $('[data-toggle="dropdown"]').parent().removeClass('open');
        return false;


    }
});
//main dash edit click
$(document).on("click", 'a[id="dash_edit"]', function () {
    //get id from href
    var contentPanelId = $(this).attr('href');
    //find div using id
    var model_sel = $('div[dashboard-panel-id="'+ contentPanelId+'"]').find('#dashboard_text');
    var model_title = $('div[dashboard-panel-id="'+ contentPanelId+'"]').find('h4');

    // add border around editable content
    model_sel.attr('contenteditable', true).css('border', '1px solid orange');
    model_title.attr('contenteditable', true).css('border', '1px solid orange');

    //change incon into save
    $(this).html('<i class="icon-ok pull-right"></i>').attr('id', 'dash_save');
    model_sel.text(model_sel.html());
    model_title.text(model_title.html());


    return false;
});
//click save in main dashboard
$(document).on("click", 'a[id="dash_save"]', function () {
    //get div id from link
    var contentPanelId = $(this).attr('href');
    //find div of model using id 
    var model_sel = $('div[dashboard-panel-id="'+ contentPanelId+'"]').find('#dashboard_text');
    var model_title = $('div[dashboard-panel-id="'+ contentPanelId+'"]').find('h4');

    //remove border from editable content
    model_sel.attr('contenteditable', false).css('border', '');
    model_title.attr('contenteditable', false).css('border', '');

    //change icon into edit
    $(this).html('<i class="icon-pencil pull-right"></i>').attr('id', 'dash_edit');
    //Send new comment to server
    console.log(model_sel.text());
    console.log(model_title.text());
    console.log(contentPanelId);
    var request = $.ajax({
        url: '/dashboard/update',
        type: 'POST',
        data: {
            content: model_sel.text(),
            title: model_title.text(),
            id: contentPanelId}
    });
    //if request was successful
    request.done(function (data) {
        //$('#test').html(data);
    });
    //if request fails (server error)
    request.fail(function (jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
    model_sel.html(model_sel.text());
    model_title.html(model_title.text());

    return false;
});
//press edit discussion
$(document).on("click", 'a[thread-id]', function () {
    //get id of the comment
    var contentPanelId = $(this).attr('thread-id');
    //find comment div
    var comment_sel = $('#comment' + contentPanelId);
    var comment_title = $('h4[thread-title="'+contentPanelId+'"]')
    //if class is edit then turn div into editbale
    if ($(this).attr('class') == "edit") {
        //enable content editable
        comment_sel.attr('contenteditable', true);
        comment_title.attr('contenteditable', true);

        //change class to save to recognize next click
        $(this).attr('class', 'save');
        //add border around editable content
        comment_sel.css('border', '1px solid orange');
        comment_title.css('border', '1px solid orange');
        comment_sel.text(comment_sel.html());
        comment_title.text(comment_title.html());
        //turn icon into save
        $('a[thread-id="' + contentPanelId + '"]').html('<i class="icon-ok"></i> Save');
        return false;
    } else {
        //chenge class to edit
        $(this).attr('class', 'edit');
        //remove border from div
        comment_sel.css('border', '');
        comment_title.css('border', '');

        //add edit icon
        $('a[thread-id="' + contentPanelId + '"]').html('<i class="icon-pencil"></i> Edit');
        //turn of contenteditable function
        comment_sel.attr('contenteditable', false);
        comment_title.attr('contenteditable', false);
        //Send new comment to server
        var request = $.ajax({
            url: window.location.pathname,
            type: 'PUT',
            data: {
                content: comment_sel.text(),
                title: comment_title.text(),
                id: contentPanelId}
        });

        //if request was successful
        request.done(function (data) {
            //$('#test').html(data);
        });

        //if request fails (server error)
        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
        comment_sel.html(comment_sel.text());
        comment_title.html(comment_title.text());
        return false;

    }
});
//click on edit comment
$(document).on("click", 'a[comment-id]', function () {
    //get id of the comment
    var contentPanelId = $(this).attr('comment-id');
    //find comment div
    var comment_sel = $('#comment' + contentPanelId);
    //if class is edit then turn div into editbale
    if ($(this).attr('class') == "edit") {
        //enable content editable
        comment_sel.attr('contenteditable', true);
        //change class to save to recognize next click
        $(this).attr('class', 'save');
        //add border around editable content
        comment_sel.css('border', '1px solid orange');
        //turn icon into save

        $('a[comment-id="' + contentPanelId + '"]').html('<i class="icon-ok"></i> Save');
        comment_sel.text(comment_sel.html());
        comment_title.text(comment_title.html());
        return false;
    } else {
        //chenge class to edit
        $(this).attr('class', 'edit');
        //remove border from div
        comment_sel.css('border', '');
        //add edit icon
        $('a[comment-id="' + contentPanelId + '"]').html('<i class="icon-pencil"></i> Edit');
        //turn of contenteditable function
        comment_sel.attr('contenteditable', false);

        //Send new comment to server
        var request = $.ajax({
            url: '/comment/edit',
            type: 'POST',
            data: {
                content: comment_sel.text(),
                id: contentPanelId}
        });

        //if request was successful
        request.done(function (data) {
            //$('#test').html(data);
        });

        //if request fails (server error)
        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
        comment_sel.html(comment_sel.text());
        comment_title.html(comment_title.text());
        return false;

    }
});
//for users in create project
var userids = [];
var project_users;
$.ajax({
    url: '/users/ajax',
    type: 'post',
    dataType: 'json',
    data: {id: $('input[data-project-id]').attr('data-project-id')},
    success: function(data) {
        project_users = data;
        if ($("#project-users").length) {
            add_users();
        }
    }

});

//check if there are users after error
function add_users(){
    //ruturning usrs and adding them into array
    if ($("#project-users").attr("users").length){
        users_str = $("#project-users").attr("users");
        userids = users_str.split(',');
        //add each user from loop into project
        $.each(project_users, function (i, state) {
	        var found = false;
		for (i = 0; i < userids.length && !found; i++) {
  		    if (userids[i] == state.id) {
    			found = true;
  		    }
		}
           	if (found) {
                //checking if last name exist in user
                if (state.last_name == null) {
                    item = state.first_name + " - " + state.email;
                    $('.table').append('<tr id="' + state.id + '"><td>' + item + '</td><td><a class="delete" href="' + state.id + '">&times;</a></td></tr>');
                } else {
                    item = state.first_name + " " + state.last_name + " - " + state.email;
                    $('.table').append('<tr id="' + state.id + '"><td>' + item + '</td><td><a class="delete" href="' + state.id + '">&times;</a></td></tr>');
                }
            }
        });
    }
}


//use for search users in create project

$('input[id="users"]').typeahead({
    //add name for search
    
    source: function (query, process) {
      
         states = [];
        map = {};
        //set search name for every users
        $.each(project_users, function (i, state) {
            //checking if last name exist in user
            if (state.last_name == null) {
                map[state.first_name + " - " + state.email] = state;
                states.push(state.first_name + " - " + state.email);
            } else {
                map[state.first_name + " " + state.last_name + " - " + state.email] = state;
                states.push(state.first_name + " " + state.last_name + " - " + state.email);
            }
        });

        process(states);
    },
    
    //send users id that been selected to select tags
    updater: function (item) {
        //retrieve user id
        selectedState = map[item].id;
        //check is user id exist in array
        if ($.inArray(selectedState, userids) == -1) {
            //add new user id
            userids.push(selectedState);
            //create table row
            $('.table').append('<tr id="' + selectedState + '"><td>' + item + '</td><td><a class="delete" href="' + selectedState + '">&times;</a></td></tr>');
            //animation
            $('#' + selectedState).hide().delay(10).fadeIn("fast");
        }
        return '';
    }
});
//remove user from table
$(document).on("click", 'a[class="delete"]', function () {
    var contentPanelId = $(this).attr('href');
    //remove id from array
    userids.splice($.inArray(contentPanelId, userids), 1);
    //remove from table
    $('#' + contentPanelId).fadeOut("fast", function () {
        $('#' + contentPanelId).remove();
    });
    return false;
});

//checks date inputs when creating project
$('#form').submit(function () {
    //send data trough post method
    $('input[name="users"]').val(userids);
});


/**
 *-----------------------MODAL BOX AJAX-----------------------
 *
 * */
$('.user-invite > form').submit(function () {

    var url = $(this).attr('action');
    var method = $(this).attr('method');

    var request = $.ajax({
        url: url,
        type: method,
        data: $(this).serialize()
    });

    //if request was successful
    request.done(function (data) {
        //debug
        //console.log(data);

        //bones for messages (there is no div at the end)
        var errorMessage = '<div class="alert alert-error ajax">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong>Oh snap!</strong> ';

        var successMessage = '<div class="alert alert-success ajax">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong>Success!</strong> ';

        //remove old information
        $('.modal-body > form .error').removeClass('error');
        $('.ajax').remove();

        //check if the server side sends back some errors
        if ($.isEmptyObject(data.errors)) {
            //empty - the data was sent without no errors

            //global successful information message
            if (data.messages.hasOwnProperty("success")) {
                //set custom message
                successMessage = successMessage + data.messages['success'] + '</div>';
            } else {
                //default message if there is no message from server-side
                successMessage = successMessage + 'The data was successfuly submited.</div>';
            }


            $('.modal-body > form').before(successMessage);

        } else {
            //not empty - there are some errors

            //global error information message
            if (data.messages.hasOwnProperty("error")) {
                //set custom message
                successMessage = successMessage + data.messages['error'] + '</div>';
            } else {
                //default message if there is no message from server-side
                successMessage = successMessage + 'There were some problems with data.</div>';
            }

            $('.modal-body > form').before(errorMessage);

            //get all the error including the key
            $.each(data.errors, function (key, value) {
                $('input[name="' + key + '"]')
                    .after('<span class="help-inline ajax">' + value + '</span>')
                    .closest('.control-group')//add error class
                    .addClass('error');
            });
        }
        //apply animation
        $('.ajax').hide().delay(10).fadeIn("fast");
    });

    //if request fails (server error)
    request.fail(function (jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });

    //disable normal form behavior
    return false;
});

//for freezing the input if one is not selected
function disable_input() {
    //getting the value of field
    var selected_value = $("#contact-type option:selected").val();

    if (!selected_value) {
        //if the value is empty
        $('#contact-info').prop('disabled', true);
    } else {
        //if the value is selected
        $('#contact-info').prop('disabled', false);
    }
}

$(document).ready(function () {

    //enable modal windows to submit forms with enter button
    $(".modal").keypress(function (event) {
        if (event.which == 13) {
            $('.modal-body > form').submit();
        }
    });

    /**
     *-----------------------DATEPICKER-----------------------
     *Uses jquery UI sortable - effects toggleClass() and removeClass()
     * */
    $("#from").datepicker({
        dateFormat: 'dd-mm-yy',
        onClose: function (selectedDate) {
            $('#to').datepicker("option", "minDate", selectedDate);
        }
    });

    $("#to").datepicker({
        dateFormat: 'dd-mm-yy',
        onClose: function (selectedDate) {
            $('#from').datepicker("option", "maxDate", selectedDate);
        }
    });

    /**
     * Setting back the priority rating in dropdown list from server
     * */
    if ($('#priority').length) {
        priority = $("#priority").attr("priority");
        $('#priority').children().each(function () {
            if ($(this).val() == priority) {
                $(this).prop('selected', 'selected');
            }
        });
    }

    /**
     * Setting the Date Of Birth on /settings/personal page from
     * using the data from #date, attribute
     * */
    //checking if the ID exists (we need to know if it is needs to be executed)
    if ($('#date').length) {
        //getting Date Of Birth
        var dob = $("#date").attr("dob").split("-");//year,month,day

        //looping through parent id to get all <option> values
        $.each(['#year', '#month', '#day'], function (key, value) {
            //looping through parents id <options>
            $(value).children().each(function () {
                //if the value of <option> matches set it as selected
                if ($(this).val() == dob[key]) {
                    $(this).prop('selected', 'selected');
                }
            });
        });
    }

    /**
     * Setting the default drop-down list in user /settings/contact page
     * */
    //checking if the ID exists (we need to know if it is needs to be executed)
    if ($('#contact-type').length) {
        selected_value = $("#contact-type").attr("selected_value");//getting the value of id
        $('#' + selected_value).prop('selected', 'selected');//setting the value
        //after setting the value check if we need to disable input
        disable_input();
    }


});

function sorting(disable) {
    /**
     *-----------------------SORTING for dashboard-----------------------
     *Uses jquery UI sortable - effects toggleClass() and removeClass()
     * */
    var sections_order = new Object(); //for storing order of sections
    var count = 0; //for counting how many times was update action done
    var sel_section = null; //first selected section for highlighting
    //function to bring back all the sections
    function sections(callback) {
        var sections = new Array("#top-section", "#left-section", "#right-section");
        for (var i = 0; i < sections.length; i++) {
            callback(sections[i]);
        }
    }

    //SORTING

    $(".sortable").sortable({
        cursor: "move",
        connectWith: ".connected-sortable",
        placeholder: "state-highlight",
        revert: 100,

        start: function () {
            //loop through all 3 dashboard sections
            sections(function (section) {
                //check if section has any content
                if (!$.trim($(section).html()).length) {
                    //add a highlighting class to the div
                    $(section).toggleClass("sorting-highlight", 200);
                }
            });
        },
        update: function () {
            count++; //incrementing on every activation
            //storing section id and section order also removing the
            //removing the section only sending the position left-section = left
            var section_id = $(this).attr('id').split("-");
            sections_order[section_id[0]] = $(this).sortable('toArray');

            //if count is 2 that means, ORDER of sections is ready to be sent
            if (count == 2) {
                //get the data-dashboard value to know where is sorting happening
                var project_id = $('div[data-dashboard]').attr('data-dashboard');
                sections_order.test = project_id;
                console.log(sections_order);
                //send the changes to server-side
                $.post('/home', sections_order);
                //console.log(sections_order);
            }
        },
        over: function () {
            //check if the 1st selected section value is set
            if (sel_section == null) {
                sel_section = '#' + $(this).attr('id');
            }

            sections(function (section) {
                //if section has 1 div
                // and the users selected section is the same as the section which has 1 div
                if ($(section + ' div').length == 1 && sel_section == section) {
                    $(section).addClass("sorting-highlight");
                }
            });
        },
        stop: function () {
            //remove all the classes
            sections(function (section) {
                $(section).removeClass("sorting-highlight", 200);
            });

            //Set the variables empty
            sel_section = null;
            sections_order = new Object();
            count = 0;
        }
    }).sortable('enable').disableSelection();

    //disable sorting
    if(disable){
        $('.sortable').sortable('disable').enableSelection();
    }
    // END sorting
}

/*Go up button script*/
//check if window size is lower than 767
if ($(window).width() <= 767) {
    //enable top button
    $(window).scroll(function () {
        if ($(this).scrollTop()) {
            $('#to-top').fadeIn();
        } else {
            $('#to-top').fadeOut();
        }
    });
}

//Slide to top action
$("#to-top, .to-top").click(function () {
    $('html, body').animate({ scrollTop: 0 }, 'fast');
});

//Enables the make tooltips

$('.tooltips').tooltip({
    selector: "[data-rel=tooltip]"
});
