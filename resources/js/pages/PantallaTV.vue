<template>
  <div class="pantalla">

    <!-- HEADER -->
    <div class="header">
      <h1>Turnero Digital</h1>
      <p>Atención al Usuario</p>
    </div>

    <!-- CONTENIDO -->
    <div class="main">

      <!-- TURNO ACTUAL -->
      <div class="actual">
        <h2>TURNO ACTUAL</h2>

        <transition name="zoom">
          <div class="numero" :key="turno.numero">
            {{ turno.numero || '---' }}
          </div>
        </transition>

        <div class="modulo">
          Módulo {{ turno.modulo || '-' }}
        </div>
      </div>

      <!-- HISTORIAL -->
      <div class="historial">
        <h2>ÚLTIMOS TURNOS</h2>

        <ul>
          <li v-for="t in historial" :key="t.id">
            <span class="num">{{ t.numero }}</span>
            <span class="mod">M{{ t.modulo }}</span>
          </li>
        </ul>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import { io } from "socket.io-client"

// 📌 estado
const turno = ref({})
const historial = ref([])

// 🔌 SOCKET
const socket = io("http://192.168.101.70:3001")

// 🔔 SONIDO
let ding = new Audio("https://www.soundjay.com/buttons/sounds/button-3.mp3")

// 🗣 VOZ NATIVA (MEJORADA)
const hablar = (texto) => {
  if (!('speechSynthesis' in window)) return

  const mensaje = new SpeechSynthesisUtterance(texto)

  const voces = speechSynthesis.getVoices()
  const voz = voces.find(v => v.lang.includes('es')) || voces[0]

  if (voz) mensaje.voice = voz

  mensaje.lang = "es-ES"
  mensaje.rate = 0.85
  mensaje.pitch = 1

  speechSynthesis.cancel()
  speechSynthesis.speak(mensaje)
}

// 🔥 CONVERTIR TURNO
const convertirTurno = (turno) => {
  if (!turno) return ''

  const mapa = {
    '0': 'cero',
    '1': 'uno',
    '2': 'dos',
    '3': 'tres',
    '4': 'cuatro',
    '5': 'cinco',
    '6': 'seis',
    '7': 'siete',
    '8': 'ocho',
    '9': 'nueve',
    'R': 'erre',
    'A': 'a',
    'I': 'i'
  }

  return turno
    .toString()
    .toUpperCase()
    .split('')
    .map(c => mapa[c] || c)
    .join(', ')
}

// 📊 HISTORIAL
const cargarHistorial = async () => {
  const res = await axios.get("http://192.168.101.70:8000/api/turnos")
  historial.value = res.data
}

onMounted(() => {

  cargarHistorial()

  // 🔥 desbloqueo audio (clave en Chrome)
  document.addEventListener('click', () => {
    speechSynthesis.resume()
  }, { once: true })

  socket.off("actualizar_turno")

  socket.on("actualizar_turno", (data) => {

    turno.value = data

    historial.value.unshift(data)
    historial.value = historial.value.slice(0, 10)

    // 🔔 sonido
    ding.currentTime = 0
    ding.play().catch(() => {})

    // 🗣 voz
    setTimeout(() => {
      const texto = convertirTurno(data.numero)
      hablar(`Turno ${texto}. Diríjase al módulo ${data.modulo}`)
    }, 800)

  })
})
</script>

<style>
.pantalla {
  background: #f1f5f9;
  height: 100vh;
  display: flex;
  flex-direction: column;
  font-family: 'Segoe UI', sans-serif;
}

/* HEADER */
.header {
  background: #0033a0;
  color: white;
  text-align: center;
  padding: 15px;
}

.header h1 {
  margin: 0;
  color: #ffcc00;
}

/* MAIN */
.main {
  display: flex;
  flex: 1;
}

/* TURNO */
.actual {
  flex: 2;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: white;
}

.numero {
  font-size: 180px;
  color: #16a34a;
  font-weight: bold;
}

.modulo {
  font-size: 50px;
  color: #dc2626;
}

/* HISTORIAL */
.historial {
  flex: 1;
  background: #e2e8f0;
  padding: 20px;
}

.historial ul {
  list-style: none;
  padding: 0;
}

.historial li {
  display: flex;
  justify-content: space-between;
  background: white;
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 8px;
}

/* ANIMACIÓN */
.zoom-enter-active {
  animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
  from {
    transform: scale(0.7);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>