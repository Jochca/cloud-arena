import { Application } from '@hotwired/stimulus'
import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers'

// start the Stimulus application
window.Stimulus = Application.start()

// załaduj wszystkie kontrolery z katalogu ./controllers
const context = require.context('./controllers', true, /\.js$/)
Stimulus.load(definitionsFromContext(context))
