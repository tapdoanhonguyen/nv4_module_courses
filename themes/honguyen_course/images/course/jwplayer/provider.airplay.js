webpackJsonpjwplayer([8],{66:function(e,t,a){var i,l;i=[a(2)],l=function(e){return function(t,a){function i(){a.set("castState",{available:a.get("castAvailable"),active:a.get("castActive")})}function l(t){t&&t.forEach(function(t){t.file=e.getAbsolutePath(t.file)})}function n(t){t&&(t.image=e.getAbsolutePath(t.image),l(t.allSources),l(t.sources))}function c(){o.removeEventListener("webkitplaybacktargetavailabilitychanged",u.updateAvailability),o.removeEventListener("webkitcurrentplaybacktargetiswirelesschanged",u.updateActive)}function r(){o.addEventListener("webkitplaybacktargetavailabilitychanged",u.updateAvailability),o.addEventListener("webkitcurrentplaybacktargetiswirelesschanged",u.updateActive)}function s(){o=null,a.getVideo()&&(o=a.getVideo().video),o&&(c(),r()),u.updateAvailability({}),u.updateActive()}var o=null,u=this;u.updateAvailability=function(e){a.set("castAvailable","available"===e.availability),i()},u.updateActive=function(){var e=!1;o&&(e=!!o.webkitCurrentPlaybackTargetIsWireless),a.off("change:playlistItem",n),e&&(t.instreamDestroy(),n(a.get("playlistItem")),a.on("change:playlistItem",n)),a.set("airplayActive",e),a.set("castActive",e),i()},u.airplayToggle=function(){o&&o.webkitShowPlaybackTargetPicker()},s(),a.on("itemReady",s)}}.apply(t,i),!(void 0!==l&&(e.exports=l))}});