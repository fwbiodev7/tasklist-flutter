import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../providers/task_provider.dart';

class ManageTasksScreen extends StatelessWidget {
  const ManageTasksScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final taskProvider = Provider.of<TaskProvider>(context);
    final allTasks = taskProvider.tasks;

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'GERENCIAR TAREFAS',
          style: TextStyle(letterSpacing: 2, fontWeight: FontWeight.bold, color: Color(0xFF00F0FF)),
        ).animate().fade().slideY(begin: -0.2),
      ),
      body: allTasks.isEmpty
          ? const Center(
              child: Text(
                'Nenhuma tarefa registrada no sistema.',
                style: TextStyle(color: Colors.grey, fontSize: 16),
              ),
            ).animate().fade(delay: 200.ms)
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: allTasks.length,
              itemBuilder: (context, index) {
                final task = allTasks[index];
                return Dismissible(
                  key: Key(task.id),
                  direction: DismissDirection.endToStart,
                  onDismissed: (direction) {
                    taskProvider.deleteTask(task.id);
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(
                        content: const Text('Tarefa apagada com sucesso.', style: TextStyle(color: Colors.white)),
                        backgroundColor: Colors.redAccent,
                        duration: const Duration(seconds: 2),
                        behavior: SnackBarBehavior.floating,
                      ),
                    );
                  },
                  background: Container(
                    margin: const EdgeInsets.only(bottom: 12),
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        colors: [Colors.red.shade900, Colors.redAccent],
                      ),
                      borderRadius: BorderRadius.circular(16),
                    ),
                    alignment: Alignment.centerRight,
                    padding: const EdgeInsets.only(right: 20),
                    child: const Icon(Icons.delete_sweep, color: Colors.white, size: 30)
                        .animate(onPlay: (controller) => controller.repeat(reverse: true))
                        .slideX(begin: 0, end: -0.2, duration: 600.ms),
                  ),
                  child: Container(
                    margin: const EdgeInsets.only(bottom: 12),
                    decoration: BoxDecoration(
                      color: const Color(0xFF161B29),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(
                        color: task.isCompleted
                            ? Colors.greenAccent.withOpacity(0.5)
                            : const Color(0xFF0055FF).withOpacity(0.5),
                        width: 1.5,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: task.isCompleted
                              ? Colors.greenAccent.withOpacity(0.1)
                              : const Color(0xFF0055FF).withOpacity(0.1),
                          blurRadius: 10,
                        ),
                      ],
                    ),
                    child: ListTile(
                      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
                      title: Text(
                        task.title,
                        style: TextStyle(
                          color: Colors.white,
                          decoration: task.isCompleted ? TextDecoration.lineThrough : null,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      subtitle: Text(
                        task.isCompleted ? 'Status: Concluído' : 'Status: Pendente',
                        style: TextStyle(
                          color: task.isCompleted ? Colors.greenAccent : const Color(0xFF00F0FF),
                        ),
                      ),
                      trailing: const Icon(Icons.swipe_left, color: Colors.grey, size: 20)
                          .animate(onPlay: (controller) => controller.repeat(reverse: true))
                          .slideX(begin: 0.2, end: 0, duration: 1000.ms),
                    ),
                  ),
                ).animate().fade(delay: (index * 100).ms).slideX(begin: 0.2, end: 0);
              },
            ),
    );
  }
}
