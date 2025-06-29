// Setup before functions
var typingTimer; // Timer identifier
var doneTypingInterval = 250;
var actionVal = "encode"; // Time in ms (0.25 seconds)
var encodedVal = "";
var messageVal = "";
$("#encodedRow").hide();

$("#check").on("change", function () {
  if ($(this).is(":checked")) {
    $("#messageRow").hide();
    $("#encodedRow").show();
    actionVal = "decode";
  } else {
    $("#messageRow").show();
    $("#encodedRow").hide();
    actionVal = "encode";
  }
  performAjaxUpdate();
});
// On keyup, start the countdown
$("#messageInput").on("keyup", function () {
  //let messageVal = $(this).val();
  if (actionVal == "encode") {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(performAjaxUpdate, doneTypingInterval);
  }
});

$("#messageInput").on("keydown", function () {
  clearTimeout(typingTimer);
});

$("#encodedInput").on("keyup", function () {
  if (actionVal == "decode") {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(performAjaxUpdate, doneTypingInterval);
  }
});

$("#messageInput").on("keydown", function () {
  clearTimeout(typingTimer);
});

$("#keyInput").on("keyup", function () {
  //let keyVal = $(this).val();
});

// On keydown, clear the countdown (optional)
// Function to perform the AJAX update

function performAjaxUpdate() {
  var keyVal = $("#keyInput").val();
  var messageVal = $("#messageInput").val();
  var encodedVal = $("#encodedInput").val();
  let dataVal = encodedVal;
  let decode = false;

  if (actionVal == "decode") {
    decode = true;
    dataVal = encodedVal;
    $("#lbl").html("Decoded:");
  } else {
    decode = false;
    dataVal = messageVal;
    $("#lbl").html("Encoded:");
  }

  if ((messageVal !== "" && !decode) || (encodedVal !== "" && decode)) {
    $.ajax({
      url: "playfair_example.php",
      method: "POST",
      data: { key: keyVal, data: dataVal, action: actionVal },
      success: function (response) {
        if (decode) {
        } else {
          $("#encodedInput").val(response);
        }
        $("#results").html(response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error: " + status + " - " + error);
      },
    });
  } else {
    $("#results").html("");
  }
}
