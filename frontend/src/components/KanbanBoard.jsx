import { useState, useEffect, useContext } from "react";
import axios from "axios";
import { AuthContext } from "../context/AuthContext";
import AddTaskForm from "./AddTaskForm";
import SmartAssignButton from "./SmartAssignButton";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";

const columns = ["Todo", "In Progress", "Done"];

function KanbanBoard() {
  const { token } = useContext(AuthContext);
  const [tasks, setTasks] = useState([]);

  const fetchTasks = async () => {
    try {
      const res = await axios.get(`${process.env.REACT_APP_API_URL}/tasks.php`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      setTasks(res.data);
    } catch (err) {
      console.error("Failed to fetch tasks", err);
    }
  };

  useEffect(() => {
    fetchTasks();
  }, []);

  const onDragEnd = async (result) => {
    if (!result.destination) return;

    const updatedTasks = [...tasks];
    const taskIndex = updatedTasks.findIndex(t => t.id.toString() === result.draggableId);

    // Update status locally
    updatedTasks[taskIndex].status = result.destination.droppableId;
    setTasks(updatedTasks);

    // Send update to backend
    try {
      await axios.put(`${process.env.REACT_APP_API_URL}/tasks.php`, updatedTasks[taskIndex], {
        headers: { Authorization: `Bearer ${token}` }
      });
    } catch (err) {
      console.error("Failed to update task", err);
    }
  };

  return (
    <div style={{ padding: "20px" }}>
      <h2>📋 Your Kanban Board</h2>

      <AddTaskForm onTaskAdded={fetchTasks} />

      <DragDropContext onDragEnd={onDragEnd}>
        <div style={{ display: "flex", justifyContent: "space-around", gap: "20px" }}>
          {columns.map(col => (
            <Droppable droppableId={col} key={col}>
              {(provided) => (
                <div
                  ref={provided.innerRef}
                  {...provided.droppableProps}
                  style={{
                    width: "30%",
                    minHeight: "400px",
                    backgroundColor: "#f5f5f5",
                    padding: "10px",
                    borderRadius: "8px"
                  }}
                >
                  <h3>{col}</h3>

                  {tasks
                    .filter(task => task.status === col)
                    .map((task, index) => (
                      <Draggable key={task.id.toString()} draggableId={task.id.toString()} index={index}>
                        {(provided) => (
                          <div
                            ref={provided.innerRef}
                            {...provided.draggableProps}
                            {...provided.dragHandleProps}
                            style={{
                              padding: "10px",
                              margin: "10px 0",
                              backgroundColor: "white",
                              border: "1px solid #ccc",
                              borderRadius: "5px",
                              ...provided.draggableProps.style
                            }}
                          >
                            <strong>{task.title}</strong>
                            <p>{task.description}</p>
                            <p>Priority: {task.priority}</p>

                            {/* Smart Assign Button */}
                            <SmartAssignButton taskId={task.id} onAssigned={fetchTasks} />
                          </div>
                        )}
                      </Draggable>
                    ))}

                  {provided.placeholder}
                </div>
              )}
            </Droppable>
          ))}
        </div>
      </DragDropContext>
    </div>
  );
}

export default KanbanBoard;
