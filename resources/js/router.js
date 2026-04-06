import { createRouter, createWebHistory } from 'vue-router'

import GenerarTurno from './pages/GenerarTurno.vue'
import Modulos from './pages/Modulos.vue'
import PantallaTV from './pages/PantallaTV.vue'

const routes = [
    { path: '/', component: GenerarTurno },
    { path: '/modulo/:id', component: Modulos },
    { path: '/pantalla', component: PantallaTV },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router




// import { createRouter, createWebHistory } from 'vue-router'


// import GenerarTurno from './pages/GenerarTurno.vue'
// import Modulos from './pages/Modulos.vue'
// import PantallaTV from './pages/PantallaTV.vue'

// const routes = [

// { path:'/turnos', component:GenerarTurno },
// { path:'/modulos', component:Modulos },
// { path:'/tv', component:PantallaTV }

// ]

// const router = createRouter({
// history: createWebHistory(),
// routes
// })

// export default router