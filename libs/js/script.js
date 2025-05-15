$(document).ready(function () {
  fetchSavedWeather();
});

function fetchSavedWeather() {
  $.ajax({
    url: "libs/php/getSavedWeather.php",
    type: "GET",
    dataType: "json",
    success: function (result) {
      if (result.status.name == "ok") {
        var savedWeatherList = $("#saveWeatherList");
        savedWeatherList.empty(); // Clear the list before appending new items

        result.data.forEach(function (weather) {
          var newListItem =
            "<li>" +
            '<div class="weather-info">' +
            '<span class="city"><img width="37" src="' +
            weather.icon +
            '" alt="icon" />' +
            weather.city +
            ", " +
            weather.country +
            "</span>" +
            '<span class="details">Temperature: ' +
            weather.temperature +
            "°C, Humidity: " +
            weather.humidity +
            ", " +
            weather.condition +
            "</span>" +
            "</div>" +
            '<button class="button delete-btn" onClick="deleteWeatherInfo(' + weather.id + ', this)">Delete</button>' +
            "</li>";

          savedWeatherList.append(newListItem);
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Handle errors here
    },
  });
}

$("#runBtn").click(function () {
  $.ajax({
    url: "libs/php/getWeatherInfo.php",
    type: "POST",
    dataType: "json",
    data: {
      cityname: $("#cityname").val(),
    },
    success: function (result) {
      console.log(result);

      if (result.status.name == "ok") {
        $("#cityname").val("");
        var country = result.data.location?.country;
        var city = result.data?.location?.name;
        var temperature = result.data?.current?.temp_c;
        var humidity = result.data?.current?.humidity;
        var condition = result.data?.current?.condition.text;
        var icon = result.data?.current?.condition.icon;

        var newListItem =
          "<li>" +
          '<div class="weather-info">' +
          '<span class="city"><img width="37" src="' +
          icon +
          '" alt="icon" />' +
          city +
          ", " +
          country +
          "</span>" +
          '<span class="details">Temperature: ' +
          temperature +
          "°C, Humidity: " +
          humidity +
          ", " +
          condition +
          "</span>" +
          "</div>" +
          '<button class="button save-btn" onclick="saveWeatherInfo(\'' +
          city +
          "','" +
          country +
          "','" +
          temperature +
          "','" +
          humidity +
          "','" +
          condition +
          "','" +
          icon +
          "', this)\">Save</button>" +
          "</li>";

        // prepend the new <li> to the ul
        $("#weatherList").prepend(newListItem);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // your error code
    },
  });
});

function saveWeatherInfo(
  city,
  country,
  temperature,
  humidity,
  condition,
  icon,
  button
) {
  $.ajax({
    url: "libs/php/saveWeatherInfo.php",
    type: "POST",
    dataType: "json",
    data: {
      city: city,
      country: country,
      temperature: temperature,
      humidity: humidity,
      condition: condition,
      icon: icon,
    },
    success: function (result) {
      console.log(result);
      if (result.status.name == "ok") {
        $(button)
          .closest("li")
          .slideUp(100, function () {
            $(this).remove(); // Remove the element after animation completes
          });
        fetchSavedWeather();
      } else {
        alert("Failed to save weather info.");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // your error code
    },
  });
}

function deleteWeatherInfo(id, listItem) {
  $.ajax({
    url: "libs/php/deleteWeather.php",
    method: "DELETE",
    dataType: "json",
    data: {
      id: id,
    },
    success: function (result) {
      console.log(result);
      if (result.status.code == "200") {
        $(listItem).closest("li").slideUp(200, function () {
          $(this).remove();
        });
      } else {
        alert("Failed to delete weather entry.");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Handle errors here
      console.error("Error deleting weather entry:", errorThrown);
    },
  });
}
