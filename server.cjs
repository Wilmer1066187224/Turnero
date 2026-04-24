const io = require("socket.io")(3001, {
    cors: {
        origin: "*"
    }
})

io.on("connection", (socket) => {

    console.log("Cliente conectado")

    socket.on("nuevo_turno", (data) => {

        console.log("Nuevo turno:", data)

        socket.broadcast.emit("actualizar_turno", data)

    })

})

