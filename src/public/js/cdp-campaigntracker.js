
class cdpAlqCampaignTracker {
  #ga4client = null;
  #campaign = null;
  #newvisit = false;
  #partner = {};
  #settings
  #defaults = { qsp: { campaign:'utm_campaign', source:  'utm_source', medium: 'utm_medium', term: 'utm_term', content: 'utm_content', matchtype: 'matchtype',}, cookieLife: 30 };
  #adKeys = {  "cid": { "partner": "unknow", "location": "referrer" }, "gclid": { "partner": "google", "location": "referrer" }, "transaction_id": { "partner": "everflow", "location": "referrer" }, "fbclid": { "partner": "facebook", "location": "referrer" }, "gclsrc": { "partner": "doubleclick", "location": "referrer" }, "mkwid": { "partner": "marin", "location": "referrer" }, "pcrid": { "partner": "marin", "location": "properties" }, "msclkid": { "partner": "bing", "location": "referrer" }, "epik": { "partner": "pintrest", "location": "referrer" }, "igshid": { "partner": "instagram", "location": "referrer" }, "gum_id": { "partner": "criteo", "location": "referrer" }, "irclickid": { "partner": "impact", "location": "referrer" }, "ttd_id": { "partner": "tradedisk", "location": "referrer" }, "clickid": { "partner": "taboola", "location": "referrer" }, "twclid": { "partner": "twitter", "location": "referrer" }, "scclid": { "partner": "snapshot", "location": "referrer" }, "ttclid": { "partner": "tiktok", "location": "referrer" }, "vmcid": { "partner": "yahoo", "location": "referrer" }, "sp_camp": { "partner": "amazon", "location": "referrer" }, "ar_camid": { "partner": "adroll", "location": "referrer" }, "cmpid": { "partner": "gemini", "location": "referrer" }, "OutbrainClickId": { "partner": "outbrain", "location": "referrer" } };

  constructor(siteSettings = {}, adKeys = {}) {
    
    this.#settings = { ...this.#defaults, ...siteSettings};
    this.#settings.adKeys = { ...this.#adKeys, ...adKeys };

    this.getGA4client();
    this.loadCurrentCampaign();
    this.updateCampaign();

    console.log("CDP Analytics: Enhanced Campaign Enabled");
    // console.log(this.#campaign);
  };

  current() {
    return { "newvist": this.#newvisit,
      "campaign": this.#campaign, 
      "partner": this.#partner, 
      "ga4_client": this.#ga4client 
    };
  };

  loadCurrentCampaign() {
    let lastCamp = localStorage.getItem('alq_cdp_ajs_camp'); 

    if ((typeof lastCamp != 'undefined') && (lastCamp != null)) {
      let lastTouch = JSON.parse(lastCamp);
      this.#campaign = (Object.keys(lastTouch.campaign).length > 0) ? lastTouch.campaign : null;
      this.#partner =  (Object.keys(lastTouch.partner).length > 0) ? lastTouch.partner : null;
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
        if (typeof this.#settings.adKeys[key] != 'undefined') {
          this.#newvisit = true;
          this.#partner.name = (this.#settings.adKeys[key].partner != 'undefined') ? this.#settings.adKeys[key].partner : "undefined";
          this.#partner.location = (this.#settings.adKeys[key].location != 'undefined') ? this.#settings.adKeys[key].location : "properties";
          this.#partner.key = key;
          this.#partner.id = value;
        }
        // lookup network info
      } else {
        this.#newvisit = true;
        currentCampagin[`${cs}`] = value;
      }
    }
    if ( (typeof currentCampagin.campaign == 'undefined') && (this.#campaign == null) ) {
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
        }

      } catch(e) {
        console.log(e.message);
      }

      currentCampagin = {
        "campaign": campaign,
        "source": source.toLowerCase(),
        "medium": "",
        "term": "",
        "content": ""
      };
    }
    if (typeof currentCampagin.campaign !== 'undefined')  {
      this.#campaign = currentCampagin;
    }
    
    this.storeCampaign();
  }

  getKeyByValue(object, value) {
    // console.log("Lookup " + value);
    return Object.keys(object).find(key => object[key] === value);
  }
	
  storeCampaign() {
    // console.log("Campaign -> " + JSON.stringify(campaignObj));
    if (this.#campaign.campaign != '') {
      let lastTouch = {
        "campaign": this.#campaign,
        "partner": this.#partner
      };
      
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

  saveGA4client(clientID) {
    this.#ga4client = clientID;
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
      if (this.#campaign == null) {
        let lastCamp = Cookies.get('alq_cdp_ajs_camp');
        
        if ((typeof lastCamp != 'undefined') && (lastCamp != null)) {
          let lastTouch = JSON.parse(lastCamp);
          this.#campaign = (Object.keys(lastTouch.campaign).length > 0) ? lastTouch.campaign : null;
          this.#partner =  (Object.keys(lastTouch.partner).length > 0) ? lastTouch.partner : null;
        }
      }
    }
  }
};

(function () {
  if ( (typeof analytics !== 'undefined') && (cdp_analytics.campaign_context == "1" ) ) {
	
    var mw = new cdpAlqCampaignTracker({}, cdp_ad_keys );
    
    const ALQUEHANCECAMP = function({ payload, next, integrations }) {
      userCamp = mw.current();
      
      if  (payload.obj.type == "page") {
        if (typeof payload.obj.properties == 'undefined') payload.obj.properties = {};

        if (Object.keys(userCamp.campaign).length > 0) {
          if (typeof payload.obj.context == 'undefined') payload.obj.context = {};
          // payload.obj.context.campaign = userCamp.campaign;
          payload.obj.properties.last_touch = userCamp.campaign;
          payload.obj.properties.last_touch.updated = userCamp.newvist;
        }

        if (Object.keys(userCamp.partner).length > 0) {
          if ((userCamp.newvist) && (userCamp.partner.location == "referrer")) {
            if (typeof payload.obj.context == 'undefined') payload.obj.context = {};
            payload.obj.context.referrer = {};
            payload.obj.context.referrer.name = userCamp.partner.name;
            payload.obj.context.referrer.id = userCamp.partner.id;
          } 
          
          payload.obj.properties.ad_network = userCamp.partner;
        }

        if (userCamp.ga4_client != null) {
          payload.obj.properties.ga4_clientId = userCamp.ga4_client;
        }
      } else if ( (payload.obj.type == "track") && (userCamp.ga4_client != null) ) {
        if (typeof payload.obj.properties == 'undefined') payload.obj.properties = {};
        payload.obj.properties.ga4_clientId = userCamp.ga4_client;
      } 

      next(payload);
    };

    analytics.addSourceMiddleware(ALQUEHANCECAMP);
  }
}());