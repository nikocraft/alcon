<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'

export default {
  extends: VuePerfectScrollbar,
}
</script>

<style>
  /* perfect-scrollbar v0.8.1 */
  .ps-container {
    -ms-touch-action: auto;
    touch-action: auto;
    overflow: hidden !important;
    -ms-overflow-style: none; }
    @supports (-ms-overflow-style: none) {
      .ps-container {
        overflow: auto !important; } }
    @media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
      .ps-container {
        overflow: auto !important; } }
    .ps-container.ps--active-x > .ps__scrollbar-x-rail,
    .ps-container.ps--active-y > .ps__scrollbar-y-rail {
      display: block;
      background-color: transparent; }
    .ps-container.ps--in-scrolling.ps--x > .ps__scrollbar-x-rail {
      background-color: #eee;
      opacity: 0.4; }
      .ps-container.ps--in-scrolling.ps--x > .ps__scrollbar-x-rail > .ps__scrollbar-x {
        background-color: #999;
        height: 11px; }
    .ps-container.ps--in-scrolling.ps--y > .ps__scrollbar-y-rail {
      background-color: #eee;
      opacity: 0.4; }
      .ps-container.ps--in-scrolling.ps--y > .ps__scrollbar-y-rail > .ps__scrollbar-y {
        background-color: #999;
        /*background-color: rgba(255,255,255,.2);*/
        width: 6px; }
    .ps-container > .ps__scrollbar-x-rail {
      display: none;
      position: absolute;
      /* please don't change 'position' */
      opacity: 0;
      -webkit-transition: background-color .2s linear, opacity .2s linear;
      -o-transition: background-color .2s linear, opacity .2s linear;
      -moz-transition: background-color .2s linear, opacity .2s linear;
      transition: background-color .2s linear, opacity .2s linear;
      bottom: 0px;
      /* there must be 'bottom' for ps__scrollbar-x-rail */
      height: 10px; }
      .ps-container > .ps__scrollbar-x-rail > .ps__scrollbar-x {
        position: absolute;
        /* please don't change 'position' */
        background-color: #aaa;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
        -webkit-transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;
        transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;
        -o-transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;
        -moz-transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;
        transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;
        transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -webkit-border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;
        bottom: 2px;
        /* there must be 'bottom' for ps__scrollbar-x */
        height: 6px; }
      .ps-container > .ps__scrollbar-x-rail:hover > .ps__scrollbar-x, .ps-container > .ps__scrollbar-x-rail:active > .ps__scrollbar-x {
        height: 11px; }
    .ps-container > .ps__scrollbar-y-rail {
      display: none;
      position: absolute;
      /* please don't change 'position' */
      opacity: 0;
      -webkit-transition: background-color .2s linear, opacity .2s linear;
      -o-transition: background-color .2s linear, opacity .2s linear;
      -moz-transition: background-color .2s linear, opacity .2s linear;
      transition: background-color .2s linear, opacity .2s linear;
      right: 0;
      /* there must be 'right' for ps__scrollbar-y-rail */
      width: 10px; }
      .ps-container > .ps__scrollbar-y-rail > .ps__scrollbar-y {
        position: absolute;
        /* please don't change 'position' */
        background-color: #aaa;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
        -webkit-transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;
        transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, -webkit-border-radius .2s ease-in-out;
        -o-transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;
        -moz-transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;
        transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out;
        transition: background-color .2s linear, height .2s linear, width .2s ease-in-out, border-radius .2s ease-in-out, -webkit-border-radius .2s ease-in-out, -moz-border-radius .2s ease-in-out;
        right: 2px;
        /* there must be 'right' for ps__scrollbar-y */
        width: 6px; }
      .ps-container > .ps__scrollbar-y-rail:hover > .ps__scrollbar-y, .ps-container > .ps__scrollbar-y-rail:active > .ps__scrollbar-y {
        width: 6px; }
    .ps-container:hover.ps--in-scrolling.ps--x > .ps__scrollbar-x-rail {
      background-color: #eee;
      opacity: 0.4; }
      .ps-container:hover.ps--in-scrolling.ps--x > .ps__scrollbar-x-rail > .ps__scrollbar-x {
        background-color: #999;
        height: 11px; }
    .ps-container:hover.ps--in-scrolling.ps--y > .ps__scrollbar-y-rail {
      background-color: #eee;
      opacity: 0.4; }
      .ps-container:hover.ps--in-scrolling.ps--y > .ps__scrollbar-y-rail > .ps__scrollbar-y {
        background-color: #999;
        width: 6px; }
    .ps-container:hover > .ps__scrollbar-x-rail,
    .ps-container:hover > .ps__scrollbar-y-rail {
      opacity: 0.2; }
    .ps-container:hover > .ps__scrollbar-x-rail:hover {
      background-color: #eee;
      opacity: 0.4; }
      .ps-container:hover > .ps__scrollbar-x-rail:hover > .ps__scrollbar-x {
        background-color: #999; }
    .ps-container:hover > .ps__scrollbar-y-rail:hover {
      background-color: #eee;
      opacity: 0.4; }
      .ps-container:hover > .ps__scrollbar-y-rail:hover > .ps__scrollbar-y {
        background-color: #999; }
</style>
