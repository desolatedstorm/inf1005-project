/* Template from Colorlib */
(function($) {

    "use strict";

    $(document).ready(function(){
        var date = new Date();
        // calendar
        $(".right-button").click({date: date}, next_month);
        $(".left-button").click({date: date}, prev_month);

        // booking
        $("#minus-btn").click(onMinusclick)
        $("#plus-btn").click(onPlusClick)

        // checkout
        $("#checkout-btn").click({date:date}, onCheckoutclick);

        init_calendar(date);
    });

    function init_calendar(date) {
        $(".tbody").empty();
        $(".timeslots-container").empty();
        $(".booking-form").hide();

        var calendar_days = $(".tbody");
        var month = date.getMonth();
        var year = date.getFullYear();
        var day_count = days_in_month(month, year);
        var row = $("<tr class='table-row'></tr>");

        var today = new Date();
        today.setHours(0,0,0,0);

        var first_day = new Date(year, month, 1).getDay();

        var isCurrentMonth = (today.getMonth() === month && today.getFullYear() === year);
        var isFutureMonth  = (year > today.getFullYear()) ||
                             (year === today.getFullYear() && month > today.getMonth());

        for (var i = 0; i < 42; i++) {
            var day = i - first_day + 1;
            var cell;

            if (i % 7 === 0) {
                calendar_days.append(row);
                row = $("<tr class='table-row'></tr>");
            }

            if (day < 1 || day > day_count) {
                cell = $("<td class='table-date nil'></td>");
                cell.prop("disabled", true);
                row.append(cell);
                continue;
            }

            var full_date = new Date(year, month, day);
            var disabled = full_date < today;

            cell = $("<td class='table-date' id='" + day + "'>" + day + "</td>");

            if (disabled) {
                cell.addClass("disabled-td");
            }

            if ($(".active-date").length === 0 && !disabled) {
                if (isCurrentMonth && day === today.getDate()) {
                    cell.addClass("active-date");
                    show_timings(full_date);
                }
                else if (isFutureMonth && day === 1) {
                    cell.addClass("active-date");
                    show_timings(full_date);
                }
            }

            cell.click({full_date: full_date, disabled: disabled}, function(e) {
                if (e.data.disabled) return;
                $(".active-date").removeClass("active-date");
                $(this).addClass("active-date");
                show_timings(e.data.full_date);
            });

            row.append(cell);
        }

        calendar_days.append(row);
        $(".month").text(months[month]);
    }

    function days_in_month(month, year) {
        return new Date(year, month + 1, 0).getDate();
    }

    function next_month(event) {
        $("#dialog").hide(250);
        var date = event.data.date;
        date.setMonth(date.getMonth() + 1);
        init_calendar(date);
    }

    function prev_month(event) {
        $("#dialog").hide(250);
        var date = event.data.date;
        date.setMonth(date.getMonth() - 1);
        init_calendar(date);
    }

    function show_timings(date) {
        console.log(date);
        var formattedDate = formatDate(date);

        $(".timeslots-container").empty();
        $(".booking-form").hide();  // reset when switching date

        bookingPost(formattedDate).done(function(response) {
            if (!response.success) {
                $(".timeslots-container").append(
                    $("<div class='event-card'><div class='event-name'>No Available Slots.</div></div>")
                );
                return;
            }

            var available_slots = response.available_slots;
            
            $(".timeslots-container").append(
                $("<div class='row mb-2 text-dark align-items-center'><div class='col-auto'><img src='images/clock.png' class='logo me-2' alt='clock icon'></div><div class='col-auto'><p class='m-0 fw-bold'>Available Slots</p></div></div>")
            );

            // timeslots wrapper
            let wrapper = $("<div class='timeslot-wrapper'></div>");
            $(".timeslots-container").append(wrapper);

            for (var i = 0; i < available_slots.length; i++) {
                let slot = available_slots[i];

                var timeslot_card = $("<div class='event-card timeslot'></div>");
                var timeslot_name = $("<div class='event-name'>" + slot + "</div>");

                timeslot_card.click({card:timeslot_card, name:timeslot_name},timeslot_click);
                
                timeslot_card.append(timeslot_name);

                wrapper.append(timeslot_card);
            }
        });

        $(".timeslots-container").show(250);
    }

    function bookingPost(date) {
        return $.ajax({
            type: 'POST',
            url: 'inc/api_booking.php',
            data: { date: date },
            dataType: 'json'
        });
    }

    /* ---------------------------- */
    /*         BOOKING FORM         */
    /* ---------------------------- */

    //temp value - get final values from actual page
    var price = 25;
    var selectedTime;
    var default_pax = 2;
    var min = 2;
    var max = 8;

    function timeslot_click(event) {
        $(".timeslot").removeClass("active-timeslot");
        event.data.card.addClass("active-timeslot"); // change background coloe to #A855F7

        $(".event-name").removeClass("active-name");
        event.data.name.addClass("active-name"); // change text to white
        
        $("#min-players").text(min);
        $("#max-players").text(max);

        $("#player-count").text(default_pax);

        selectedTime = event.data.card.find(".event-name").text(); // store selected time for checkout page
        
        $(".booking-form").show(250);
        updateSubtotal();
    }

    function onMinusclick() {
        var pax = parseInt($("#player-count").text());
        if (pax > 2) {
            pax = pax - 1
        }
        else {
            pax = 2
        }
        $("#player-count").text(pax);
        updateSubtotal(pax, price);
    }

    function onPlusClick() {
        var pax = parseInt($("#player-count").text());
        if (pax < 8) {
            pax = pax + 1
        }
        else {
            pax = 8
        }
        $("#player-count").text(pax);
        updateSubtotal(pax, price);
    }

    function updateSubtotal() {
        var subtotal = 0;
        var pax = parseInt($("#player-count").text());
        if (pax >= 2 && pax <= 8) { // 2 - 8 pax
            var subtotal = pax * price;
        }
        $(".pax").text(pax);
        $(".ticket-price").text(price);
        $("#subtotal").text(subtotal);
    }

    function onCheckoutclick(event) {
        // room id/name - get from caller page
        var room_id = 1; //tmp
        // user id/name - get from cookies
        var user_id = 1; //temp
        // craft selected date
        var selectedDay = $(".active-date").attr("id");
        var month = months.indexOf($(".month").text());
        var year = event.data.date.getFullYear();

        var selectedDate = new Date(year, month, selectedDay);
        var formattedDate = formatDate(selectedDate);

        var pax = parseInt($("#player-count").text());
        var subtotal = parseFloat($("#subtotal").text());

        console.log("Checkout info:", {
            user: user_id,
            room: room_id,
            date: formattedDate,
            time: selectedTime,
            pax: pax,
            subtotal: subtotal
        });

        // ajax to checkout api, api inserts booking hold
        
        // find out how to use dummy payment + confirm booking
        // update booking to confirm status and send success response
        // finally update page to show booking confirmation
        // send email confirmaiton as well
    }

    function formatDate(date) {
        var formattedDate = date.getFullYear() + '-' + 
                            String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                            String(date.getDate()).padStart(2, '0');
        return formattedDate
    }

    // query db for room name, desc, min, max, price peak + off peak using caller id
    
    const months = [ 
        "January","February","March","April","May","June",
        "July","August","September","October","November","December"
    ];

})(jQuery);
