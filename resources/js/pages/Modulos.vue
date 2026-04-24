<template>
  <div class="modulo-container">

    <!-- HEADER -->
    <div class="header">
      <h1>Módulo {{ modulo }}</h1>
      <p>Atención al Usuario</p>
    </div>

    <!-- CONTENIDO -->
    <div class="contenido">

      <!-- BOTÓN -->
      <button @click="llamar" class="btn-llamar">
        Llamar Turno
      </button>

      <!-- TURNO -->
      <transition name="zoom">
        <div v-if="turno" class="turno-box" :key="turno.numero">

          <p class="label">Turno en atención</p>

          <div class="numero">
            {{ turno.numero }}
          </div>

          <div class="modulo-text">
            Módulo {{ modulo }}
          </div>

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

const socket = io("http://192.168.101.70:3001")

const turno = ref(null)

const route = useRoute()
const modulo = route.params.id

const llamar = async () => {
  try {
    const res = await axios.post(`http://192.168.101.70:8000/api/llamar/${modulo}`)
    turno.value = res.data
    socket.emit("nuevo_turno", res.data)
  } catch (error) {
    console.error("ERROR:", error)
  }
}
</script>

<style scoped>

/* 🌐 CONTENEDOR GENERAL */
.modulo-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: #f4f6f9;
  font-family: 'Segoe UI', sans-serif;
}

/* 🔝 HEADER */
.header {
  text-align: center;
  padding: 25px 10px;
  background: #ffffff;
  border-bottom: 1px solid #e5e7eb;
}

.header h1 {
  font-size: 34px;
  margin: 0;
  color: #1f2937;
  font-weight: 600;
}

.header p {
  color: #6b7280;
  margin-top: 5px;
}

/* 📦 CONTENIDO */
.contenido {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* 🔘 BOTÓN */
.btn-llamar {
  background: #2563eb;
  color: white;
  font-size: 20px;
  padding: 16px 50px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  box-shadow: 0 8px 20px rgba(37,99,235,0.2);
  transition: all 0.3s ease;
}

.btn-llamar:hover {
  background: #1d4ed8;
  transform: translateY(-2px);
}

/* 🧾 TARJETA TURNO */
.turno-box {
  margin-top: 50px;
  background: white;
  padding: 40px 70px;
  border-radius: 20px;
  text-align: center;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  border: 1px solid #e5e7eb;
}

/* TEXTO */
.label {
  font-size: 18px;
  color: #6b7280;
  margin-bottom: 10px;
}

/* 🔢 NÚMERO GRANDE */
.numero {
  font-size: 110px;
  font-weight: bold;
  color: #111827;
}

/* 📍 MÓDULO */
.modulo-text {
  margin-top: 10px;
  font-size: 22px;
  color: #2563eb;
  font-weight: 600;
}

/* ✨ ANIMACIÓN */
.zoom-enter-active {
  animation: zoomIn 0.35s ease;
}

@keyframes zoomIn {
  from {
    transform: scale(0.85);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

</style>