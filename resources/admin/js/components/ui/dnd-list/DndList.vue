<template>
    <ol class="dd-list">
        <li class="dd-item" v-for="item in data" :data-id="item.uniqueId">
            <div class="dd-item-one">
                <component-wrapper-functional
                    :type="item.type"
                    :show-content="false"
                    :uniqueId="item.uniqueId"
                    v-on:remove="removeItem(item.uniqueId)"
                    :key="item.uniqueId">
                </component-wrapper-functional>
            </div>
            <dnd-list v-if="item.children" :data="item.children"></dnd-list>
        </li>
    </ol>
</template>

<script>
import ComponentWrapperFunctional from '~/views/design/menus/items/ComponentWrapperFunctional'

export default {
    name: 'DndList',
    components: {
        ComponentWrapperFunctional
    },
    props: {
        data: {type: Array},
    },
    data() {
        return {
            //
        }
    },
    methods: {
        removeItem() {
            console.log('Remove Item Test')
        }
    }
}
</script>
