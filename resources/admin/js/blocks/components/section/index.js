import Block from './Block'
import Settings from './Settings'

export default {
    block: Block,
    settings: Settings,
    type: 'layout',
    disallowedList: [
        'column',
        'slide',
    ],
    showFooter: false,
    topToolbar: ['addComponents']
}
