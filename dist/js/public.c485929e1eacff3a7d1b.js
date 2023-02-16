/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/public/js/cdp-analytics.js":
/*!****************************************!*\
  !*** ./src/public/js/cdp-analytics.js ***!
  \****************************************/
/***/ (() => {

eval("function _typeof(obj) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && \"function\" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }, _typeof(obj); }\nfunction _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\nfunction _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : \"undefined\" != typeof Symbol && arr[Symbol.iterator] || arr[\"@@iterator\"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0) { ; } } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i[\"return\"] && (_r = _i[\"return\"](), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\nfunction _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }\nfunction _toPropertyKey(arg) { var key = _toPrimitive(arg, \"string\"); return _typeof(key) === \"symbol\" ? key : String(key); }\nfunction _toPrimitive(input, hint) { if (_typeof(input) !== \"object\" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || \"default\"); if (_typeof(res) !== \"object\") return res; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (hint === \"string\" ? String : Number)(input); }\nfunction _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== \"undefined\" && o[Symbol.iterator] || o[\"@@iterator\"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === \"number\") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError(\"Invalid attempt to iterate non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it[\"return\"] != null) it[\"return\"](); } finally { if (didErr) throw err; } } }; }\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(o); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }\nif (typeof analytics == 'undefined') {\n  // Insert Server Side Implementation\n}\njQuery(document).ready(function ($) {\n  ajsTrackLinkCallback = function ajsTrackLinkCallback(destUrl) {\n    window.location.href = destUrl;\n  };\n  ajsTrackSocialShare = function ajsTrackSocialShare(href, lnk) {\n    var shareLink = href != '' && href != '#' ? href : lnk.attr(cdp_analytics.social_selector.replace(/\\[/g, '').replace(/\\]/g, ''));\n    var sharedTo = shareLink.includes(\"facebook.com\") ? \"facebook\" : \"unkown\";\n    sharedTo = shareLink.includes(\"twitter.com\") ? \"twitter\" : sharedTo;\n    sharedTo = shareLink.includes(\"linkedin.com\") ? \"linkedin\" : sharedTo;\n    sharedTo = shareLink.includes(\"pinterest.com\") ? \"pinterest\" : sharedTo;\n    analytics.track('Content Shared', {\n      \"channel\": sharedTo,\n      \"title\": $(\"title\").text(),\n      \"url\": $(location).attr(\"href\"),\n      \"path\": $(location).attr(\"pathname\"),\n      \"description\": $('meta[name=\"description\"]').attr('content')\n    });\n    return false;\n  };\n  ajsInteractiveLink = function ajsInteractiveLink(href, lnk) {\n    var elEv = {};\n    elEv.non_interactive = false;\n    elEv.label = lnk.text().replace(\"’\", \"'\");\n    elEv.link_url = href;\n    elEv.link_type = 'engagement';\n    analytics.track('Link Clicked', elEv);\n    return true;\n  };\n  ajsTrackLinkClick = function ajsTrackLinkClick(href, lnk) {\n    var linkInfo = new URL(href);\n    var filetypes = /\\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|txt)$/i;\n    var baseHref = $('base').attr('href') != undefined ? $('base').attr('href') : '';\n    var sendTrack = true;\n    var requiresCallback = false;\n    var elEv = {};\n    elEv.non_interactive = false;\n    // elEv.name = 'Link Clicked';\n    elEv.label = lnk.text().replace(\"’\", \"'\");\n    elEv.link_url = href;\n    if (href.match(/^mailto\\:/i)) {\n      elEv.link_type = 'email';\n      //elEv.email_recipient = href.replace(/^mailto\\:/i, '');\n      elEv.email_recipient = linkInfo.path[0];\n      if (linkInfo.searchParams.has(\"subject\")) elEv.subject = linkInfo.searchParams.get(\"subject\");\n    } else if (href.match(filetypes)) {\n      var extension = /[.]/.exec(href) ? /[^.]+$/.exec(href) : undefined;\n      elEv.link_type = 'download';\n      elEv.file_type = extension[0];\n      // elEv.click_url = href.replace(/ /g,'-');\n      elEv.link_url = baseHref + href;\n      requiresCallback = lnk.attr('target') == undefined || lnk.attr('target').toLowerCase() != '_blank';\n    } else if (href.match(/^https?\\:/i)) {\n      elEv.link_type = 'external';\n      elEv.site_referred = linkInfo.host;\n      // elEv.click_url = href.replace(/^https?\\:\\/\\//i, '');\n      // elEv.non_interactive = true;\n      requiresCallback = lnk.attr('target') == undefined || lnk.attr('target').toLowerCase() != '_blank';\n    } else if (href.match(/^tel\\:/i)) {\n      elEv.link_type = 'telephone';\n      elEv.number_dialed = href.replace(/^tel\\:/i, '');\n    } else {\n      sendTrack = false;\n    }\n    if (sendTrack) {\n      if (requiresCallback) {\n        analytics.track(\"Link Clicked\", elEv, null, ajsTrackLinkCallback(elEv.link_url));\n        return false;\n      } else {\n        analytics.track(\"Link Clicked\", elEv);\n        return true;\n      }\n    } else {\n      return true;\n    }\n  };\n  console.log(\"CDP Analytics: Load Link Tracking\");\n  if (cdp_analytics.track_links == \"1\") {\n    console.log(\"CDP Analytics: Tracking Enabled\");\n    $('a[href^=\"http\"]').filter(function () {\n      var fb = this.href.includes(\"facebook.com/sharer\");\n      var twt = this.href.includes(\"twitter.com/intent/tweet\") || this.href.includes(\"twitter.com/share\");\n      var lnkd = this.href.includes(\"linkedin.com/shareArticle\");\n      var pin = this.href.includes(\"pinterest.com/pin/create/button\");\n      return fb || twt || lnkd || pin;\n    }).attr({\n      rel: \"social\"\n    });\n    // $(\"[\" + cdp_analytics.social_selector + \"]\").attr({rel: \"social\" });\n    $(cdp_analytics.social_selector).attr({\n      rel: \"social\"\n    });\n    if (cdp_analytics.force_new_window == \"1\") {\n      console.log(\"CDP Analytics: Force New Window Enabled\");\n      $('a[href^=\"http\"]').filter(function () {\n        return this.hostname && this.hostname !== location.hostname;\n      }).attr({\n        target: \"_blank\",\n        rel: \"external\"\n      });\n    }\n    $(\"a\").click(function () {\n      var el = $(this);\n      var href = typeof el.attr('href') != 'undefined' ? el.attr('href') : '';\n      var isThisDomain = href.match(document.domain.split('.').reverse()[1] + '.' + document.domain.split('.').reverse()[0]);\n      if (el.attr(\"rel\") == \"social\") {\n        return ajsTrackSocialShare(href, el);\n      } else if (href.match(/^javascript:/i) || href == '' || href == '#') {\n        return ajsInteractiveLink(href, el);\n      } else if (href.match(/^https?\\:/i) && isThisDomain) {\n        return true;\n      } else {\n        return ajsTrackLinkClick(href, el);\n      }\n    });\n  }\n  console.log(\"CDP Analytics: Link Tracking Completed\");\n  if (cdp_analytics.accordian_enable == \"1\") {\n    $(cdp_analytics.accordian_selector).click(function () {\n      // let acord = $(this);\n      var clickText = $(this).text().trim();\n      if (clickText == \"\") {\n        var children = $(this).children;\n        for (var i = 0; i < children.length; i++) {\n          clickText = children[i].text().trim();\n          if (clickText != \"\") break;\n        }\n      }\n      analytics.track(cdp_analytics.accordian_event, {\n        \"Text Clicked\": clickText == \"\" ? \"UNKNOWN\" : clickText\n      });\n      return true;\n    });\n    console.log(\"CDP Analytics: Accordian Tracking\");\n  }\n  if (typeof analytics !== 'undefined' && cdp_analytics.taxonomy_context == \"1\") {\n    var ADDWPTAX = function ADDWPTAX(_ref) {\n      var payload = _ref.payload,\n        next = _ref.next,\n        integrations = _ref.integrations;\n      if (payload.obj.type == \"page\") {\n        if (cdp_analytics.categories != \"\") payload.obj.properties.categories = cdp_analytics.categories;\n        if (cdp_analytics.tags != \"\") payload.obj.properties.tags = cdp_analytics.tags;\n      }\n      next(payload);\n    };\n    analytics.addSourceMiddleware(ADDWPTAX);\n    console.log(\"CDP Analytics: Taxonomy Tracking Enabled\");\n  }\n  if (typeof analytics !== 'undefined' && cdp_analytics.campaign_context == \"1\") {\n    expires = localStorage.getItem('cdp_analytics_expires');\n    if (expires != null) {\n      var rightNow = new Date();\n      if (rightNow > new Date(expires)) {\n        localStorage.removeItem('cdp_analytics_properties');\n        localStorage.removeItem('cdp_analytics_referrer');\n        localStorage.removeItem('cdp_analytics_campaign');\n        localStorage.removeItem('cdp_analytics_expires');\n        console.log(\"Referral Expired \" + expires);\n      }\n    }\n    var checkURL = new URL(location.href);\n    var lastTouch = {};\n    var _iterator = _createForOfIteratorHelper(checkURL.searchParams),\n      _step;\n    try {\n      for (_iterator.s(); !(_step = _iterator.n()).done;) {\n        var _step$value = _slicedToArray(_step.value, 2),\n          key = _step$value[0],\n          value = _step$value[1];\n        console.log(\"\".concat(key, \", \").concat(value));\n        if (key == 'utm_medium') lastTouch.medium = value;\n        if (key == 'utm_source') lastTouch.source = value;\n        if (key == 'utm_campaign') lastTouch.campaign = value;\n        if (key == 'utm_content') lastTouch.content = value;\n        if (key == 'utm_term') lastTouch.term = value;\n        if (key == 'utm_id') lastTouch.id = value;\n      }\n    } catch (err) {\n      _iterator.e(err);\n    } finally {\n      _iterator.f();\n    }\n    cdp_analytics.click_ids.forEach(function (partner) {\n      /*\n      for (let key in partner) {\n      \t console.log(`${key}: ${partner[key]}`);\n      }\n      */\n      if (checkURL.searchParams.has(partner['opt-qs-param'])) {\n        if (Object.keys(lastTouch).length > 0) {\n          lastTouch[partner['opt-qs-param']] = checkURL.searchParams.get(partner['opt-qs-param']);\n        }\n        if (partner['opt-location'] == 'properties') {\n          var _enhancedCampaign;\n          enhancedCampaign = (_enhancedCampaign = {}, _defineProperty(_enhancedCampaign, partner['opt-qs-param'], checkURL.searchParams.get(partner['opt-qs-param'])), _defineProperty(_enhancedCampaign, \"id\", checkURL.searchParams.get(partner['opt-qs-param'])), _defineProperty(_enhancedCampaign, \"param\", partner['opt-qs-param']), _defineProperty(_enhancedCampaign, \"type\", partner['opt-ad-platform']), _enhancedCampaign);\n          localStorage.setItem('cdp_analytics_properties', JSON.stringify(enhancedCampaign));\n        } else {\n          enhancedCampaign = {\n            \"id\": checkURL.searchParams.get(partner['opt-qs-param']),\n            \"type\": partner['opt-ad-platform']\n          };\n          localStorage.setItem('cdp_analytics_referrer', JSON.stringify(enhancedCampaign));\n        }\n        //if (checkURL.searchParams[partner['opt-qs-param']]) {\n        // console.log(\"Referred by: \" + partner['opt-ad-platform']);\n        var expDate = new Date(); // Now\n        expDate.setDate(expDate.getDate() + 30);\n        localStorage.setItem('cdp_analytics_expires', expDate);\n      }\n    });\n    if (Object.keys(lastTouch).length > 0) {\n      localStorage.setItem('cdp_analytics_campaign', JSON.stringify(lastTouch));\n    }\n    var ADDCAMP = function ADDCAMP(_ref2) {\n      var payload = _ref2.payload,\n        next = _ref2.next,\n        integrations = _ref2.integrations;\n      if (payload.obj.type == \"page\" || payload.obj.type == \"track\" && cdp_analytics.campaign_track == \"1\") {\n        referralValues = JSON.parse(localStorage.getItem('cdp_analytics_referrer'));\n        if (referralValues != null && typeof referralValues.type !== 'undefined') {\n          if (typeof payload.obj.context == 'undefined') payload.obj.context = {};\n          payload.obj.context.referrer = referralValues;\n        }\n        if (Object.keys(lastTouch).length > 0) {\n          if (typeof payload.obj.context == 'undefined') payload.obj.context = {};\n          payload.obj.context.campaign = lastTouch;\n        }\n        propertyValues = JSON.parse(localStorage.getItem('cdp_analytics_properties'));\n        if (propertyValues != null && typeof propertyValues.type != 'undefined') {\n          if (typeof payload.obj.properties == 'undefined') payload.obj.properties = {};\n          payload.obj.properties.referred_by = propertyValues;\n        }\n      }\n      if (payload.obj.type == \"identify\" && cdp_analytics.campaign_identify == \"1\" || payload.obj.type == \"group\" && cdp_analytics.campaign_group == \"1\") {\n        referralValues = JSON.parse(localStorage.getItem('cdp_analytics_referrer'));\n        if (referralValues != null && typeof referralValues.type !== 'undefined') {\n          if (typeof payload.obj.context == 'undefined') payload.obj.context = {};\n          payload.obj.context.referrer = referralValues;\n        }\n        if (Object.keys(lastTouch).length > 0) {\n          if (typeof payload.obj.context == 'undefined') payload.obj.context = {};\n          payload.obj.context.campaign = lastTouch;\n        }\n        propertyValues = JSON.parse(localStorage.getItem('cdp_analytics_properties'));\n        if (propertyValues != null && typeof propertyValues.type != 'undefined') {\n          if (typeof payload.obj.traits == 'undefined') payload.obj.traits = {};\n          payload.obj.traits.referred_by = propertyValues;\n        }\n      }\n      next(payload);\n    };\n    analytics.addSourceMiddleware(ADDCAMP);\n    console.log(\"CDP Analytics: Campaign Enhancement Enabled\");\n  }\n  if (cdp_analytics.enable_video == \"1\") {\n    window.onYouTubeIframeAPIReady = function () {\n      console.log(\"Prepare YouTube\");\n    };\n    console.log(\"CDP Analytics: Video Tracking Enabled\");\n  }\n});\n\n//# sourceURL=webpack://cdp-analytics/./src/public/js/cdp-analytics.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./src/public/js/cdp-analytics.js"]();
/******/ 	
/******/ })()
;