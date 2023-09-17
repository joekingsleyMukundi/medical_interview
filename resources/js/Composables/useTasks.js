import Axios from "axios";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

export default function useTasks() {
    const tasks = ref([]);
    const task = ref({});
    const stati = ref([]);
    const messages = ref([]);

    /*
     * Fetch listing of Tasks */
    const getTasks = async () => {
        let res = await Axios.get("/api/tasks");
        console.log(res.data);
        tasks.value = res.data;
    };

    /*
     * Fetch one Task */
    const getTask = async (id) => {
        let res = await Axios.get(`/api/tasks/${id}`);
        task.value = res.data;
    };

    /*
     * Create Task */
    const onCreateTask = async (name, description, dueDate, selectedDepartment, status) => {
        try {
            const res = await Axios.post("/api/tasks", {
                name: name,
                description: description,
                dueDate: dueDate,
                department: selectedDepartment,
                status: status
            });
            console.log(selectedDepartment);
            messages.value = [res.data];

            // Redirect
            router.get("/tasks");
            console.log(selectedDepartment);
        } catch (err) {
            console.log(err);
            const resErrors = err.response.data.errors;
            var newError = [];
            for (var resError in resErrors) {
                newError.push(resErrors[resError]);
            }
            // Get other errors
            messages.value = newError;
        }
    };

    /*
     * Update Task */
    const onUpdateTask = async (taskId) => {
        console.log({name: task.value.name,
                description: task.value.description,
                department: task.value.department,
                status: task.value.category,});
        try {
            let res = await Axios.post(`/api/tasks/${taskId}`, {
                _method: "PUT",
                name: task.value.name,
                description: task.value.description,
                department: task.value.department,
                status: task.value.category,
            });

            messages.value = [res.data];

            // Fetch Task
            getTask(taskId);
        } catch (err) {
            const resErrors = err.response.data.errors;
            var newError = [];
            for (var resError in resErrors) {
                newError.push(resErrors[resError]);
            }
            // Get other errors
            messages.value = newError;
        }
    };

    /*
     * Delete Task */
    const onDeleteTask = async (taskId) => {
        let res = await Axios.delete(`/api/tasks/${taskId}`);

        messages.value = [res.data];

        // Redirect
        setTimeout(() => {
            router.get("/tasks");
        }, 1000);
    };

    /*
     * Get Statuses */
    const getStati = async () => {
        let res = await Axios.get("/api/status");
        stati.value = res.data;
    };

    /*
     * Change Task Status */
    const onStatus = async (statusId, taskId) => {
        let res = await Axios.post(`/api/tasks/${taskId}`, {
            _method: "PUT",
            statusId: statusId,
        });

        messages.value = [res.data];

        // Fetch Task
        getTask(taskId);
    };

    /*
     * Assign User Task */
    const onAssign = async (userId, taskId) => {
        let res = await Axios.post("/api/user-tasks", {
            userId: userId,
            taskId: taskId,
        });

        messages.value = [res.data];

        // Fetch Task
        getTask(taskId);
    };

    /*
     * Change Due Date */
    const onDueDate = async (dueDate, taskId) => {
        let res = await Axios.post(`/api/tasks/${taskId}`, {
            _method: "PUT",
            dueDate: dueDate,
        });

        messages.value = [res.data];
        getTask(taskId);
    };

    return {
        tasks,
        task,
        stati,
        messages,
        getTasks,
        getTask,
        getStati,
        onStatus,
        onAssign,
        onDueDate,
        onUpdateTask,
        onCreateTask,
        onDeleteTask,
    };
}
