'use strict'
const desiredDate = new Date('2024-04-30');
const audioSource = 'https://cdn.freesound.org/previews/533/533869_5828667-lq.mp3';
const audio = new Audio(audioSource);
const getAppointmentId = () => {
  const pathSegments = window.location.pathname.split('/');
  console.log(pathSegments[4]);
  return pathSegments[4];
};

const appointmentId = getAppointmentId();

const intervalInMinutes = 1;

const notifyUser = async (message) => {
  await audio.play();
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notifications.");
  } else if (Notification.permission === "granted") {
    new Notification(message);
  } else if (Notification.permission !== "denied") {
    const permission = await Notification.requestPermission();
    if (permission === "granted") {
      new Notification(message);
    }
  }
};

const checkAppointments = setInterval(function () {
  $.getJSON('https://ais.usvisa-info.com/en-et/niv/schedule/' + appointmentId + '/appointment/days/19.json?appointments[expedite]=false', function (data) {
    if (data.length === 0) { console.log('No appointments available'); }
    console.log(data, 'DATA FETCHED');
    if (data.length > 0) {
      const earliestDate = new Date(data[0].date);
      if (earliestDate.getTime() <= desiredDate.getTime()) {
        notifyUser('Earliest date available: ' + earliestDate.toDateString());
      } else {
        console.log('No date available');
      }
    }
  });
}, 60*1000*intervalInMinutes);


