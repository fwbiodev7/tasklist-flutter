import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../providers/task_provider.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final taskProvider = Provider.of<TaskProvider>(context);
    final pendingTasks = taskProvider.pendingTasks;

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'TAREFAS PENDENTES',
          style: TextStyle(letterSpacing: 2, fontWeight: FontWeight.bold, color: Color(0xFF00F0FF)),
        ).animate().fade(duration: 500.ms).slideY(begin: -0.2, end: 0),
      ),
      body: pendingTasks.isEmpty
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.done_all, size: 80, color: const Color(0xFF00F0FF).withOpacity(0.5))
                      .animate(onPlay: (controller) => controller.repeat(reverse: true))
                      .scaleXY(end: 1.1, duration: 1000.ms)
                      .shimmer(duration: 2000.ms, color: const Color(0xFF00F0FF)),
                  const SizedBox(height: 20),
                  Text(
                    'Nenhuma tarefa pendente.\nVocê está livre!',
                    textAlign: TextAlign.center,
                    style: TextStyle(fontSize: 18, color: Colors.grey.shade400),
                  ).animate().fade(delay: 300.ms),
                ],
              ),
            )
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: pendingTasks.length,
              itemBuilder: (context, index) {
                final task = pendingTasks[index];
                return Container(
                  margin: const EdgeInsets.only(bottom: 16),
                    decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: [
                        const Color(0xFF1A1A2E),
                        const Color(0xFF16213E).withOpacity(0.9),
                      ],
                      begin: Alignment.topLeft,
                      end: Alignment.bottomRight,
                    ),
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(
                      color: const Color(0xFF00F0FF).withOpacity(0.3),
                      width: 1.5,
                    ),
                    boxShadow: [
                      BoxShadow(
                        color: const Color(0xFF00F0FF).withOpacity(0.1),
                        blurRadius: 20,
                        spreadRadius: -5,
                      ),
                    ],
                  ),
                  child: ListTile(
                    contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                    title: Text(
                      task.title,
                      style: const TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                    subtitle: Padding(
                      padding: const EdgeInsets.only(top: 8.0),
                      child: Text(
                        task.description,
                        style: TextStyle(color: Colors.grey.shade400),
                      ),
                    ),
                    trailing: InkWell(
                      borderRadius: BorderRadius.circular(20),
                      onTap: () async {
                        // Play sound or vibration here ideally
                        // We use a small delay to let the check animation play out
                        // before the state changes and the widget is removed
                        await Future.delayed(const Duration(milliseconds: 400));
                        taskProvider.toggleTaskCompletion(task.id);
                      },
                      child: const CheckButtonWidget(),
                    ),
                  ),
                ).animate().fade(delay: (index * 100).ms).slideX(begin: 0.2, end: 0);
              },
            ),
    );
  }
}

class CheckButtonWidget extends StatefulWidget {
  const CheckButtonWidget({super.key});

  @override
  State<CheckButtonWidget> createState() => _CheckButtonWidgetState();
}

class _CheckButtonWidgetState extends State<CheckButtonWidget> with SingleTickerProviderStateMixin {
  bool isChecked = false;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        setState(() {
          isChecked = true;
        });
      },
      child: Container(
        width: 36,
        height: 36,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          color: isChecked ? const Color(0xFF00F0FF) : Colors.transparent,
          border: Border.all(
            color: isChecked ? const Color(0xFF00F0FF) : const Color(0xFF00F0FF).withOpacity(0.5),
            width: 2,
            style: BorderStyle.solid,
          ),
          boxShadow: [
            if (isChecked)
              BoxShadow(
                color: const Color(0xFF00F0FF).withOpacity(0.8),
                blurRadius: 20,
                spreadRadius: 5,
              ),
          ],
        ),
        child: isChecked
            ? const Icon(Icons.check, color: Colors.black, size: 24)
                .animate()
                .scale(begin: const Offset(0.5, 0.5), end: const Offset(1.2, 1.2), duration: 200.ms, curve: Curves.elasticOut)
                .then()
                .scale(begin: const Offset(1.2, 1.2), end: const Offset(1, 1), duration: 100.ms)
            : null,
      ).animate(target: isChecked ? 1 : 0).scaleXY(end: 1.1, duration: 150.ms),
    );
  }
}
