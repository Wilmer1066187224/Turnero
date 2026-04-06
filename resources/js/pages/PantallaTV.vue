<template>
    <div class="pantalla">

        <!-- HEADER -->
        <div class="header">
            <h1>Turnero Digital</h1>
            <p>Atención al Usuario</p>
        </div>

        <!-- CONTENIDO -->
        <div class="main">

            <!-- IZQUIERDA -->
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

            <!-- DERECHA -->
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

const turno = ref({})
const historial = ref([])

// 🔌 Socket correcto
const socket = io("http://192.168.101.75:3001")

// 🔔 sonido protegido (TV safe)
let ding = null
try {
    ding = new Audio("https://www.soundjay.com/buttons/sounds/button-3.mp3")
} catch (e) {
    console.log("Audio no soportado")
}

// 🗣 voz protegida (TV safe)
const hablar = (texto) => {
    try {
        if (!('speechSynthesis' in window)) return

        const mensaje = new SpeechSynthesisUtterance(texto)
        mensaje.lang = "es-ES"

        window.speechSynthesis.speak(mensaje)
    } catch (error) {
        console.log("Voz no soportada")
    }
}

// 🔢 formateo seguro
const formatearNumero = (numero) => {
    if (!numero) return ''
    return parseInt(numero.toString().replace(/\D/g, ''))
}

// 📊 cargar historial (⚠️ SIN :8000)
const cargarHistorial = async () => {
    try {
        const res = await axios.get("http://192.168.101.75/api/turnos")
        historial.value = res.data
    } catch (error) {
        console.log("Error cargando historial")
    }
}

onMounted(() => {

    cargarHistorial()

    socket.off("actualizar_turno")

    socket.on("actualizar_turno", (data) => {
        try {
            if (!data || !data.numero) return

            turno.value = data

            historial.value.unshift(data)
            historial.value = historial.value.slice(0, 10)

            const numero = formatearNumero(data.numero)

            // 🔔 sonido seguro
            if (ding) {
                ding.currentTime = 0
                ding.play().catch(() => {})
            }

            // 🗣 voz segura
            setTimeout(() => {
                hablar(`Turno ${numero}, dirigirse al módulo ${data.modulo}`)
            }, 600)

        } catch (error) {
            console.log("Error en TV:", error)
        }
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

.header p {
    margin: 0;
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

.historial h2 {
    text-align: center;
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
    font-size: 20px;
    border-radius: 8px;
}

.num {
    font-weight: bold;
    color: #0033a0;
}

.mod {
    color: #16a34a;
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