import { useState, useContext } from "react";
import axios from "axios";
import { AuthContext } from "../context/AuthContext";

function AddTaskForm({ onTaskAdded }) {
  const { token, user } = useContext(AuthContext);
  const [title, setTitle] = useState("");
  const [description, setDescription] = useState("");
  const [priority, setPriority] = useState("Medium");

  const handleAdd = async () => {
    try {
      const res = await axios.post(`${process.env.REACT_APP_API_URL}/tasks.php`, {
        title,
        description,
        assigned_to: user.id,
        priority
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      if (res.data.status === "created") {
        alert("Task Created ✅");
        setTitle("");
        setDescription("");
        onTaskAdded(); // refresh board
      }
    } catch (err) {
      alert("Error creating task");
    }
  };

  return (
    <div style={{ margin: "10px 0" }}>
      <input value={title} onChange={e => setTitle(e.target.value)} placeholder="Task Title" />
      <input value={description} onChange={e => setDescription(e.target.value)} placeholder="Task Description" />
      <select value={priority} onChange={e => setPriority(e.target.value)}>
        <option>Low</o
