(function () {
  const beaconData = window.__HELPSCOUT_BEACON__;
  if (!beaconData || !beaconData.beacon_id) {
    return;
  }

  const script = document.createElement('script');
  script.src = beaconData.beacon_script_url;
  script.addEventListener('load', function () {
    window.Beacon('init', beaconData.beacon_id);
    window.Beacon('identify', beaconData.user);
  });
  document.body.appendChild(script);
})();
