"use strict";var _createClass=function(){function r(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,n){return t&&r(e.prototype,t),n&&r(e,n),e}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}!function(n,e){n.Swiper=function(e){function t(){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments))}return _inherits(t,n.Quiz),_createClass(t,[{key:"name",get:function(){return"swiper"}},{key:"answerSortable",get:function(){return!1}},{key:"resultSortable",get:function(){return!1}},{key:"videoUpload",get:function(){return!1}},{key:"questionMediaType",get:function(){return!1}},{key:"answerType",get:function(){return!1}}]),t}(),e(document).ready(function(){e('.wp-quiz-backend[data-type="swiper"]').each(function(){new n.Swiper(e(this))})})}(wpQuizAdmin,jQuery);