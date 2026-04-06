<template>
  <div class="modulo-container">
    <!-- HEADER -->
    <div class="header">
      <h1>🧑‍💼 Módulo {{ modulo }}</h1>
      <p>Atención al Usuario</p>
    </div>

    <!-- BOTÓN LLAMAR -->
    <div class="contenido">
      <button @click="llamar" class="btn-llamar">
        🔔 Llamar Turno
      </button>

      <!-- TURNO ACTUAL -->
      <transition name="fade">
        <div v-if="turno" class="turno-actual" :key="turno.numero">
          <p>Turno actual</p>
          <h1>{{ turno.numero }}</h1>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import axios from "axios"
import { ref } from "vue"
import { io } from "socket.io-client"
import { useRoute } from 'vue-router'

// 🔌 socket: cambia localhost por la IP de tu servidor
const socket = io("http://192.168.101.20:3001")

// 📌 estado
const turno = ref(null)

// 📍 obtener módulo desde la URL
const route = useRoute()
const modulo = route.params.id

// 🔥 función para llamar turno
const llamar = async () => {
  try {
    // axios apuntando a la IP del servidor
    const res = await axios.post(`http://192.168.101.20:8000/api/llamar/${modulo}`)
    turno.value = res.data
    console.log("Turno llamado:", res.data)

    // 🔴 enviar a la pantalla mediante Socket.IO
    socket.emit("nuevo_turno", res.data)
  } catch (error) {
    console.error("ERROR:", error)
  }
}
</script>

<style scoped>
.modulo-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
  background-color: #ffffff; /* Fondo blanco */
  color: #333333;
  font-family: 'Segoe UI', sans-serif;
  padding: 40px 20px;
}

/* HEADER */
.header {
  text-align: center;
  margin-bottom: 40px;
}

.header h1 {
  font-size: 36px;
  color: #006400; /* verde suave */
  margin: 0;
}

.header p {
  font-size: 18px;
  color: #555555;
  margin-top: 5px;
}

/* BOTÓN LLAMAR */
.btn-llamar {
  background-color: #FCD116; /* amarillo banco colombiano */
  color: #002D62; /* azul rey para contraste */
  font-size: 22px;
  padding: 18px 50px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-llamar:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

/* TURNO ACTUAL */
.turno-actual {
  margin-top: 50px;
  background: #f9f9f9; /* caja clara */
  padding: 30px 50px;
  border-radius: 20px;
  text-align: center;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.turno-actual p {
  font-size: 20px;
  color: #006400; /* verde suave */
  margin-bottom: 10px;
  font-weight: 500;
}

.turno-actual h1 {
  font-size: 90px;
  margin: 0;
  color: #FCD116; /* amarillo destacado */
  text-shadow: 0 0 10px rgba(252,209,22,0.4);
}

/* TRANSICIÓN */
.fade-enter-active, .fade-leave-active {
  transition: all 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: scale(0.85);
}
</style>