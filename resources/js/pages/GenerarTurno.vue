<template>
  <div class="turnos-container">
    <h1>🧾 Generar Turno</h1>
    <p>Seleccione el programa:</p>

    <div class="botones">
      <button class="btn renta" @click="generar('renta')">Renta Ciudadana</button>
      <button class="btn adulto" @click="generar('adulto')">Colombia Mayor</button>
      <button class="btn iva" @click="generar('iva')">Devolución IVA</button>
    </div>

    <transition name="fade">
      <div v-if="turno" class="turno-generado">
        <p>Turno generado:</p>
        <h1>{{ turno?.numero }}</h1>
      </div>
    </transition>
  </div>
</template>

<script setup>
import axios from "axios"
import { ref } from "vue"

// 📌 estado del turno
const turno = ref(null)

// 🔥 función para generar turno apuntando al servidor por IP
const generar = async (servicio) => {
  try {
    const res = await axios.post(`http://192.168.101.70:8000/api/turno/${servicio}`)
    console.log("RESPUESTA COMPLETA:", res.data)
    turno.value = res.data
  } catch (error) {
    console.error("ERROR API:", error)
  }
}
</script>

<style scoped>
.turnos-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
  background-color: #ffffff;
  font-family: 'Segoe UI', sans-serif;
  padding: 50px 20px;
  text-align: center;
}

.turnos-container h1 {
  font-size: 36px;
  color: #002D62;
  margin-bottom: 10px;
}

.turnos-container p {
  font-size: 18px;
  color: #555555;
  margin-bottom: 40px;
}

.botones {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 300px;
}

.btn {
  padding: 20px 0;
  font-size: 20px;
  border: none;
  border-radius: 15px;
  cursor: pointer;
  color: white;
  font-weight: bold;
  box-shadow: 0 6px 15px rgba(0,0,0,0.2);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* Colores por servicio */
.renta {
  background-color: #FCD116;
  color: #002D62;
}

.adulto {
  background-color: #22c55e;
}

.iva {
  background-color: #38bdf8;
}

/* Turno generado */
.turno-generado {
  margin-top: 50px;
  background: #f3f4f6;
  padding: 30px 50px;
  border-radius: 20px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.turno-generado p {
  font-size: 20px;
  color: #555555;
  margin-bottom: 10px;
}

.turno-generado h1 {
  font-size: 80px;
  margin: 0;
  color: #002D62;
  text-shadow: 0 0 10px rgba(0,45,98,0.5);
}

/* Animación */
.fade-enter-active, .fade-leave-active {
  transition: all 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: scale(0.8);
}
</style>