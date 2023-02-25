
class cdpAlqCampaignTracker {
  #ga4client = {};
  #campaign = {};
  #newcamp = false;
  #newpart = false;
  #partner = {};
  #settings
  #defaults = { qsp: { campaign:'utm_campaign', source:  'utm_source', medium: 'utm_medium', term: 'utm_term', content: 'utm_content', matchtype: 'matchtype',}, cookieLife: 30, "dev_mode":"0","campaign_context":"1","campaign_track":"1","campaign_identify":"0","campaign_group":"0","campaign_partner_tracking":"1" };
  #adKeys = {  "cid": { "partner": "unknow", "location": "referrer" }, "gclid": { "partner": "google", "location": "referrer" }, "evrflwid": { "partner": "everflow", "location": "referrer" }, "fbclid": { "partner": "facebook", "location": "referrer" }, "gclsrc": { "partner": "doubleclick", "location": "referrer" }, "mkwid": { "partner": "marin", "location": "referrer" }, "pcrid": { "partner": "marin", "location": "properties" }, "msclkid": { "partner": "bing", "location": "referrer" }, "epik": { "partner": "pintrest", "location": "referrer" }, "igshid": { "partner": "instagram", "location": "referrer" }, "gum_id": { "partner": "criteo", "location": "referrer" }, "irclickid": { "partner": "impact", "location": "referrer" }, "ttd_id": { "partner": "tradedisk", "location": "referrer" }, "clickid": { "partner": "taboola", "location": "referrer" }, "twclid": { "partner": "twitter", "location": "referrer" }, "scclid": { "partner": "snapshot", "location": "referrer" }, "ttclid": { "partner": "tiktok", "location": "referrer" }, "vmcid": { "partner": "yahoo", "location": "referrer" }, "sp_camp": { "partner": "amazon", "location": "referrer" }, "ar_camid": { "partner": "adroll", "location": "referrer" }, "cmpid": { "partner": "gemini", "location": "referrer" }, "OutbrainClickId": { "partner": "outbrain", "location": "referrer" } };

  constructor(siteSettings = {}, adKeys = {}) {
    
    this.#settings = { ...this.#defaults, ...siteSettings};
    this.#settings.adKeys = { ...this.#adKeys, ...adKeys };

    this.getGA4client();
    this.getGA4session();
    this.loadCurrentCampaign();
    this.updateCampaign();

    console.log("CDP Analytics: Enhanced Campaign Enabled");
    // console.log(this.#campaign);
  };

  current() {
    let campVal = { ...this.#campaign, ...{ updated: this.#newcamp }};
    let partVal = { ...this.#partner, ...{ updated: this.#newpart }};

    return { 
      "campaign": campVal, 
      "partner": partVal, 
      "ga4_client": this.#ga4client,
      "settings": this.#settings 
    };
  };

  loadCurrentCampaign() {
    let lastCamp = localStorage.getItem('alq_cdp_ajs_camp'); 

    if ((typeof lastCamp != 'undefined') && (lastCamp != null)) {
      let lastTouch = JSON.parse(lastCamp);
      this.#campaign = (lastTouch.campaign != null) && (Object.keys(lastTouch.campaign).length > 0) ? lastTouch.campaign : {};
      this.#partner =  (lastTouch.partner != null) && (Object.keys(lastTouch.partner).length > 0) ? lastTouch.partner : {};
    }

    this.getCookies();
  };

  updateCampaign() {
    let checkURL = new URL(location.href);
    let currentCampagin = {};
    for (const [key, value] of checkURL.searchParams) {
      // console.log(`${key}, ${value}`);
      let cs = this.getKeyByValue(this.#settings.qsp, key);
      if (typeof cs == 'undefined') {
        if ( (typeof this.#settings.adKeys[key] != 'undefined') && (this.#settings.campaign_partner_tracking) ) {
          this.#newpart = true;
          this.#partner.name = (this.#settings.adKeys[key].partner != 'undefined') ? this.#settings.adKeys[key].partner : "unknown";
          this.#partner.location = (this.#settings.adKeys[key].location != 'undefined') ? this.#settings.adKeys[key].location : "properties";
          this.#partner.key = key;
          this.#partner.id = value;
        }
        // lookup network info
      } else {
        this.#newcamp = true;
        currentCampagin[`${cs}`] = value;
      }
    }
    if ( (Object.keys(currentCampagin).length == 0) && (Object.keys(this.#campaign).length == 0) ) {
    // if (typeof currentCampagin.campaign == 'undefined') {
      let source = '';
      let campaign = '';
      try {
        if (typeof document.referrer != 'undefined') {
          var a=document.createElement('a');
          a.href = document.referrer;
        }
        if (a.hostname != location.hostname) {
          source = a.hostname;
          campaign = 'seo';

          currentCampagin = {
            "campaign": campaign,
            "source": source.toLowerCase()
          }
        }

      } catch(e) {
        console.log(e.message);
      }
    }
    
    // if (typeof currentCampagin.campaign !== 'undefined')  {
    if ((Object.keys(currentCampagin).length > 0)) {
      this.#campaign = currentCampagin;
    }
    
    this.storeCampaign();
  }

  getKeyByValue(object, value) {
    // console.log("Lookup " + value);
    return Object.keys(object).find(key => object[key] === value);
  }
	
  storeCampaign() {

    let lastTouch = {};
    if (Object.keys(this.#campaign).length > 0) lastTouch.campaign = this.#campaign;
    if (Object.keys(this.#partner).length > 0) lastTouch.partner = this.#partner;
    if (Object.keys(lastTouch).length > 0) {
      localStorage.setItem('alq_cdp_ajs_camp', JSON.stringify(lastTouch));
      this.setCookies(lastTouch);
    }
  }

  getGA4client() {
    var parent = this;
    let interval = setInterval(function (){
        if (Cookies.get('_ga')) {
          parent.saveGA4client(Cookies.get('_ga').slice(6)); 
          clearInterval(interval);
        }
    }, 100);
  };

  getGA4session() {
    // Doesn't really work, need to know Measurement Code
    var parent = this;
    let interval = setInterval(function (){
        if (Cookies.get('_gid')) {
          parent.saveGA4session(Cookies.get('_gid').split('.')); 
          clearInterval(interval);
        }
    }, 100);
  };

  saveGA4client(clientID) {
    this.#ga4client.client_id = clientID;
  };

  saveGA4session(sessionVal) {
    this.#ga4client.session_id = sessionVal[sessionVal.length - 1];
  };

  setCookies(lastTouch) {
    if (typeof Cookies != 'undefined') {
      let lifetime = (typeof(this.#settings.cookieLife) === "number") ? this.#settings.cookieLife : 30;
      // { path: '/', expires: lifetime, secure: true, sameSite: 'Lax'  }
      Cookies.set('alq_cdp_ajs_camp', JSON.stringify(lastTouch), { path: '/', expires: lifetime, sameSite: 'Lax'  } );
    } 
  }

  getCookies() {
    // Fall back to cookies if values are not in local storage
    if (typeof Cookies != 'undefined') {
      // if (this.#campaign == null) {
      if (Object.keys(this.#campaign).length == 0) {
        let lastCamp = Cookies.get('alq_cdp_ajs_camp');
        
        if ((typeof lastCamp != 'undefined') && (lastCamp != null)) {
          let lastTouch = JSON.parse(lastCamp);
          this.#campaign = (Object.keys(lastTouch.campaign).length > 0) ? lastTouch.campaign : {};
          this.#partner =  (Object.keys(lastTouch.partner).length > 0) ? lastTouch.partner : {};
        }
      }
    }
  }
};

(function () {
  if ( (typeof analytics !== 'undefined') && (cdp_analytics.campaign_context == "1" ) ) {
	
    var mw = new cdpAlqCampaignTracker(cdp_analytics, cdp_ad_keys );
    
    const ALQUEHANCECAMP = function({ payload, next, integrations }) {
      userCamp = mw.current();
      if (userCamp.settings.dev_mode) console.log(userCamp);

      if  (payload.obj.type == "page") {
        if (typeof payload.obj.properties == 'undefined') payload.obj.properties = {};

        if (Object.keys(userCamp.campaign).length > 0) {
          if (typeof payload.obj.context == 'undefined') payload.obj.context = {};
          payload.obj.properties.last_touch = userCamp.campaign;
        }

        if (Object.keys(userCamp.partner).length > 0) {
          if ((userCamp.partner.updated) && (userCamp.partner.location == "referrer")) {
            if (typeof payload.obj.context == 'undefined') payload.obj.context = {};
            payload.obj.context.referrer = {};
            payload.obj.context.referrer.name = userCamp.partner.name;
            payload.obj.context.referrer.id = userCamp.partner.id;
          } 
          
          if (userCamp.partner.updated) payload.obj.properties[userCamp.partner.key] = userCamp.partner.id;
          payload.obj.properties.ad_network = userCamp.partner;
        }

        if (Object.keys(userCamp.ga4_client).length > 0) {
          payload.obj.properties.ga4_session = userCamp.ga4_client;
        }
      } else if (payload.obj.type == "track") { 
        if (typeof payload.obj.properties == 'undefined') payload.obj.properties = {};
        
        if (userCamp.settings.campaign_track) {
          if (Object.keys(userCamp.campaign).length > 0) {
            payload.obj.properties.last_touch = userCamp.campaign;
          }
          if (Object.keys(userCamp.partner).length > 0)  {
            payload.obj.properties.ad_network = userCamp.partner;
          }

          if (Object.keys(userCamp.ga4_client).length > 0) {
            payload.obj.properties.ga4_session = userCamp.ga4_client;
          }
        }

      } else if ((userCamp.settings.campaign_identify) && (payload.obj.type == "identify")) { 
        if (typeof payload.obj.traits == 'undefined') payload.obj.traits = {};
        // if (userCamp.ga4_client != null) payload.obj.traits.ga4_clientId = userCamp.ga4_client;
        if ((Object.keys(userCamp.campaign).length > 0)) {
          payload.obj.traits.last_touch = userCamp.campaign;
        }
        if (Object.keys(userCamp.partner).length > 0)  {
          payload.obj.traits.ad_network = userCamp.partner;
        }

        if (Object.keys(userCamp.ga4_client).length > 0) {
          payload.obj.traits.ga4_session = userCamp.ga4_client;
        }
      } else if ((userCamp.settings.campaign_group) && (payload.obj.type == "group")) { 
        if (typeof payload.obj.traits == 'undefined') payload.obj.traits = {};
        // if (userCamp.ga4_client != null) payload.obj.traits.ga4_clientId = userCamp.ga4_client;
        if ((Object.keys(userCamp.campaign).length > 0)) {
          payload.obj.traits.last_touch = userCamp.campaign;
        }
        if (Object.keys(userCamp.partner).length > 0)  {
          payload.obj.traits.ad_network = userCamp.partner;
        }

        if (Object.keys(userCamp.ga4_client).length > 0) {
          payload.obj.traits.ga4_session = userCamp.ga4_client;
        }
      } 

      next(payload);
    };

    analytics.addSourceMiddleware(ALQUEHANCECAMP);
  }
}());