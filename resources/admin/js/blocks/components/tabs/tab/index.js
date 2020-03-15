import Block from './Block'
import Settings from './Settings'

export default {
	block: Block,
	settings: Settings,
	type: 'layout',
	parent: 'tabs',
    disallowedList: [
        'container',
        'tabs',
        'columns',
        'column',
        'slide'
    ],
	topToolbar: ['addComponents']
}
