<template>
    <div class="dd">
        <dnd-list :data="data" :key="key"></dnd-list>
    </div>
</template>

<script>
// Wrapper for doMenu
// https://github.com/mechanicious/domenu

const flatten = (a, parent = null, r) => {
    if (!r) {
        r = []
    }

    _.forEach(a, (o) => {
        if(o.children) {
            flatten(o.children, o.id, r)
        }
        r.push({
            id: o.id,
            parentId: parent,
            children: _.map(o.children, 'id')
        })
    })

    return r
}

import domenu from './do-menu'
import DndList from './DndList'

export default {
    components: {
        DndList
    },
    props: {
        data: {type: Array}
    },
    data() {
        return {
            key: null
        }
    },
    watch: {
        data (val, old) {
            this.forceUpdate()
        }
    },
    mounted() {
        let dm = domenu(this.$el, {})

        dm.on(['onItemDrop'], (a, b, c) => {
            let data = flatten( JSON.parse(dm.toJson()) )
            this.$emit('change', data)
        })
    },
    methods: {
        forceUpdate() {
            this.key = Date.now()
        }
    }
}
</script>

<style lang="scss">
/**
 * @license Copyright © 2016 Mateusz Zawartka
 * @version 0.99.77
 */
.dd {
    position: relative;
    display: inline-block;
    margin: 0;
    padding: 0;
    // width: 60%;
    width: 100%;
    list-style:  none;
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-size:   13px;
    line-height: 20px;
}

.dd-item-blueprint {
    display: none;
}

.dd > .dd-list {
    min-height: 80px;
}

.dd-list {
    display:    block;
    position:   relative;
    margin:     0;
    padding:    0;
    list-style: none;
}

.dd-list .dd-list {
    left: 30px;
    margin-right: 30px;
}

.dd-collapsed .dd-list {
    display: none;
}

.dd-item,
.dd-empty,
.dd-placeholder {
  // text-shadow: 0 1px 0 #fff;
  // display:     block;
  // position:    relative;
  // margin:      0;
  // padding:     0;
  // min-height:  20px;
  // font-size:   13px;
  // line-height: 20px;
}

.dd-item-one {
    margin: 5px 0;
}

.dd-item > button {
    display:     inline-block;
    position:    relative;
    cursor:      pointer;
    float:       left;
    width:       24px;
    height:      20px;
    margin:      5px 5px 5px 30px;
    padding:     0;
    white-space: nowrap;
    overflow:    hidden;
    border:      0;
    background:  transparent;
    font-size:   12px;
    line-height: 1;
    text-align:  center;
    font-weight: bold;
    color:       black;
}

.dd.domenu .dd-new-item {
    background: transparent;
    border: 3px dotted #8F8F8F;
    border-radius: 11px;
    width: 100%;
    height: 35px;
    font-size: 29px;
    color: #8F8F8F;
    outline: none;
}

/* @since > 0.13.29 */
.dd-item .item-remove,
.dd-item .item-remove-confirm,
.dd-item .item-add {
    outline: none;
}

.dd-item .dd-button-container button {
    background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.51), rgba(0, 0, 0, 0.14));
    border:           1px solid #898989;
    border-radius:    2px;
    font:             normal 12px/18px Helvetica, Lato, Arial sans-serif;
    cursor:           pointer;
}

.dd-item .dd-button-container .item-add {
    background-color: #55b3ff;
    border-color:     #376f9c;
    color:            #ffffff;
    text-shadow:      0 1px 0 #2b77b5;
    box-shadow:       inset 0 1px 0 #cfe9ff;
}

.dd-item .dd-button-container .item-add:active {
    box-shadow: inset 0 1px 3px #376f9c;
}

.dd-item .dd-button-container .item-remove {
    background-color: #ff5555;
    border-color:     #a54b4b;
    color:            #5a1111;
    text-shadow:      0 1px 0 #ffc0c0;
    box-shadow:       inset 0 1px 0 #ffc0c0;
    transition:       background-color 0.35s;
}

.dd-item .dd-button-container .item-remove:active {
    box-shadow: inset 0 1px 5px #a54b4b;
}

.dd-item .dd-button-container .item-remove-confirm {
    background-color: #ffa155;
    transition:       background-color 0.35s;
    border-color:     #ad7232;
}

.dd-item .dd-button-container .item-remove-confirm:active {

}

.item-remove-confirm {
    background: #ffce66;
}

.dd-item .dd-button-container {
    position: absolute;
    height:   19px;
    padding:  0 5px;
    top:      4px;
    overflow: visible;
    display:  none;
    right:    0;
}

/* end @since > 0.13.29 */

.dd3-item > button:first-child {
    margin-left: 30px;
}

.dd-item > button:before {
    display:     block;
    position:    absolute;
    width:       100%;
    text-align:  center;
    text-indent: 0;
}

.dd-placeholder,
.dd-empty {
    margin:          5px 0;
    padding:         0;
    min-height:      30px;
    background: #D6F2FF;
    border: 1px dashed #6C9AB1;
    box-sizing:      border-box;
    -moz-box-sizing: border-box;
}

.dd-placeholder.max-depth {
    background: #ffb3b7;
    border: 1px dashed #b14444;
}

.dd-empty {
    border:              1px dashed #bbb;
    min-height:          100px;
    background-color:    #e5e5e5;
    background-image:    linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size:     60px 60px;
    background-position: 0 0, 30px 30px;
}

.dd-dragel {
    height:         60px;
    position:       absolute;
    pointer-events: none;
    z-index:        9999;
}

.dd-dragel > .dd-item .dd-handle {
    margin-top: 0;
}

/**
 * doMenu Draggable Handles
 */
.dd3-content {
    display:               block;
    height:                100%;
    margin:                5px 0;
    padding:               5px 10px 5px 40px;
    color:                 #333;
    text-decoration:       none;
    font-weight:           bold;
    border:                1px solid #ccc;
    border:                1px solid #898989;
    background:            #fafafa;
    background:            linear-gradient(to top, #f4f4f4 10%, #c9c9c9 100%);
    -webkit-border-radius: 3px;
    border-radius:         3px;
    box-sizing:            border-box;
    -moz-box-sizing:       border-box;
}

.dd3-content:hover {
    color:      #2ea8e5;
    background: #c8c8c8;
    background: linear-gradient(to top, #e5e5e5 10%, #ffffff 100%);
}

.dd-dragel > .dd3-item > .dd3-content {
    margin: 0;
}

.dd3-handle:hover {
    background: #c8c8c8;
    background: linear-gradient(to top, #c8c8c8 0%, #8c8c8c 100%);
}

.dd3-content:hover .dd-button-container {
    display:    block;
    transition: display 2s;
}

.dd .dd-new-item {
    width: 100%;
    border: 2px dashed #BDBDBD;
    background: transparent;
    border-radius: 3px;
    font-size: 21px;
    color: #BDBDBD;
    cursor: pointer;
    transition: border 0.35s ease 0s, color 0.35s ease 0s;
    outline: none;
}

.dd .dd-new-item:hover {
    border: 2px dashed #595858;
    color: #595858;
    transition: border 0.35s ease 0s, color 0.35s ease 0s;
}
</style>
