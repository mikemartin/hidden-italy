Statamic.booting(() => {
  let helpscout = Statamic.$config.get('helpscout');

  if (helpscout && helpscout.beacon_id) {
    if (helpscout.avatar) {
      helpscout.user['avatar'] = helpscout.user['website'] + helpscout.avatar;
    }

    let script = document.createElement('script');
    script.src = helpscout.beacon_script_url;

    script.addEventListener('load', () => {
      window.Beacon('init', helpscout.beacon_id);
      window.Beacon('identify', helpscout.user);
    });

    document.body.appendChild(script);
  }
});
